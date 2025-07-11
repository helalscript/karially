<?php

namespace App\Services\API\V1\User;

use App\Models\Week;
use App\Models\WeeklyTask;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class WeeklyTaskService
{
    protected $user;
    protected $aiGeneratorService;

    public function __construct(AIGeneratorService $aiGeneratorService)
    {
        $this->aiGeneratorService = $aiGeneratorService;
        $this->user = Auth::user();
    }
    /**
     * Fetch all resources.
     *
     * @return mixed
     */
    public function index($request)
    {
        try {
            $perPage = $request->get('per_page', 10);

            // Step 1: Fetch paginated tasks
            $weeklyTasks = Week::where('user_id', $this->user->id)
                ->with('weeklyTasks:id,week_id,title,is_completed')
                ->whereNull('career_goal_id')
                ->select('id', 'week_number', 'title', 'description', 'status', 'created_at')
                ->latest()
                ->get();
            // ->paginate($perPage);

            // Step 2: Count task stats
            $totalTasks = Week::where('user_id', $this->user->id)
                ->whereNull('career_goal_id')
                ->count();

            $inProgressCount = Week::where('user_id', $this->user->id)
                ->whereNull('career_goal_id')
                ->where('status', 'in_progress')
                ->count();

            $completedCount = Week::where('user_id', $this->user->id)
                ->whereNull('career_goal_id')
                ->where('status', 'completed')
                ->count();

            // Step 3: Calculate percentages
            $completionPercentage = $totalTasks > 0
                ? round(($completedCount / $totalTasks) * 100)
                : 0;

            $inProgressPercentage = $totalTasks > 0
                ? round(($inProgressCount / $totalTasks) * 100)
                : 0;

            // Step 4: Return full response
            return [
                'status' => true,
                'message' => 'Weekly tasks retrieved successfully.',
                'code' => 200,
                'meta' => [
                    'total_tasks' => $totalTasks,
                    'in_progress_count' => $inProgressCount,
                    'completed_count' => $completedCount,
                    'completion_percentage' => $completionPercentage,
                    'in_progress_percentage' => $inProgressPercentage,
                ],
                'data' => $weeklyTasks,
            ];

        } catch (Exception $e) {
            Log::error("WeeklyTaskService::index " . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Store a new resource.
     *
     * @param array $validatedData
     * @return mixed
     */
    public function store(array $validatedData)
    {
        try {
            $aiResponse = null;

            // Step 1: Generate AI tasks if title & description are present
            if (!empty($validatedData['title']) && !empty($validatedData['description'])) {
                $aiResponseRaw = $this->aiGeneratorService->generateWeeklyTask(
                    $validatedData['title'],
                    $validatedData['description']
                );
                $aiResponse = json_decode($aiResponseRaw, true);
            }

            // Step 2: Get next week's metadata (start, end, number)
            $meta = $this->getNextWeekMeta($this->user->id);

            // Step 3: Set base data for the new Week
            $validatedData['user_id'] = $this->user->id;
            $validatedData['start_date'] = $meta['start_date'];
            $validatedData['end_date'] = $meta['end_date'];
            $validatedData['week_number'] = $meta['week_number'];
            $validatedData['description'] = $aiResponse['weeks_description'] ?? $validatedData['description'] ?? '';
            if ($meta['start_date']->greaterThan(Carbon::today())) {
                $validatedData['status'] = 'not_started';
            } elseif ($meta['start_date']->lessThanOrEqualTo(Carbon::today()) && $meta['end_date']->greaterThan(Carbon::today())) {
                $validatedData['status'] = 'in_progress';
            } else {
                $validatedData['status'] = 'completed';
            }

            // Step 4: Create the new Week
            $week = Week::create($validatedData);

            // Step 5: Create AI-generated tasks if available
            if (!empty($aiResponse['tasks']) && is_array($aiResponse['tasks'])) {
                $tasks = collect($aiResponse['tasks'])->map(function ($task) {
                    return ['title' => $task['title']];
                });
                $week->weeklyTasks()->createMany($tasks->toArray());
            }

            return $week;

        } catch (Exception $e) {
            Log::error("WeeklyTaskService::store: " . $e->getMessage());
            throw $e;
        }
    }



    /**
     * Display a specific resource.
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        try {
            $week = Week::where('user_id', $this->user->id)
                ->whereNull('career_goal_id')
                ->with('weeklyTasks:id,week_id,title,is_completed')
                ->select('id', 'week_number', 'title', 'description', 'status', 'created_at')
                ->find($id);
            if (!$week) {
                throw new Exception("Week not found");
            }
            return $week;
        } catch (Exception $e) {
            Log::error("WeeklyTaskService::show" . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Update a specific resource.
     *
     * @param int $id
     * @param array $validatedData
     * @return mixed
     */
    public function update(int $id, array $validatedData)
    {
        try {
            $week = Week::where('user_id', $this->user->id)
                ->whereNull('career_goal_id')
                ->find($id);
            if (!$week) {
                throw new Exception("Week not found");
            }
            $week->update($validatedData);
            return $week;
        } catch (Exception $e) {
            Log::error("WeeklyTaskService::update" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a specific resource.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        try {
            $week = Week::where('user_id', $this->user->id)
                ->whereNull('career_goal_id')
                ->find($id);
            if (!$week) {
                throw new Exception("Week not found");
            }
            $week->delete();
            return true;
        } catch (Exception $e) {
            Log::error("WeeklyTaskService::destroy" . $e->getMessage());
            throw $e;
        }
    }


    public function markAsCompleted(int $id)
    {
        try {
            $week = Week::where('user_id', $this->user->id)
                ->whereNull('career_goal_id')
                ->find($id);
            if (!$week) {
                throw new Exception("Week not found");
            }
            if ($week->status === 'completed') {
                throw new Exception("Week is already marked as completed");
            }
            $week->status = 'completed';
            $week->save();
            //also mark all weekly tasks as completed
            $week->weeklyTasks()->update(['is_completed' => true, 'status' => 'completed']);
            return $week;
        } catch (Exception $e) {
            Log::error("WeeklyTaskService::markAsCompleted" . $e->getMessage());
            throw $e;
        }
    }

    public function getNextWeekMeta(int $userId): array
    {
        $lastTask = Week::where('user_id', $userId)
            ->whereNull('career_goal_id')
            ->orderBy('end_date', 'desc')
            ->first();

        if ($lastTask) {
            $nextStart = Carbon::parse($lastTask->end_date)->addDay();
            $startDate = $nextStart->greaterThan(Carbon::today()) ? $nextStart : Carbon::today();
            $weekNumber = $lastTask->week_number + 1;
        } else {
            $startDate = Carbon::today();
            $weekNumber = 1;
        }

        $endDate = $startDate->copy()->addDays(6);

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'week_number' => $weekNumber
        ];
    }

    public function markAsCompleteSingleTask(int $weeklyTaskId)
    {
        try {
            $weeklyTask = WeeklyTask::find($weeklyTaskId);
            if (!$weeklyTaskId) {
                throw new Exception("Weekly task not found");
            }
            if ($weeklyTask->is_completed) {
                throw new Exception("Weekly task is already marked as completed");
            }
            // Mark the task as completed
            $weeklyTask->is_completed = true;
            $weeklyTask->status = 'completed';
            $weeklyTask->save();
            return $weeklyTask;
        } catch (Exception $e) {
            Log::error("WeeklyTaskService::markAsCompleteSingleTask" . $e->getMessage());
            throw $e;
        }
    }
}