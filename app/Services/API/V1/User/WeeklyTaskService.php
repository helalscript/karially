<?php

namespace App\Services\API\V1\User;

use App\Models\Week;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class WeeklyTaskService
{
    protected $user;

    public function __construct()
    {
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
                ->whereNull('career_goal_id')
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
            $validatedData['user_id'] = $this->user->id;
            $lastWeek = Week::where('user_id', $this->user->id)->whereNull('career_goal_id')->latest()->first();
            $validatedData['week_number'] = $lastWeek ? $lastWeek->week_number + 1 : 1;
            $validatedData['status'] = 'in_progress'; // Optional, can be set later
            $week = Week::create($validatedData);
            return $week;
        } catch (Exception $e) {
            Log::error("WeeklyTaskService::store" . $e->getMessage());
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
            // Logic to delete a specific resource
        } catch (Exception $e) {
            Log::error("WeeklyTaskService::destroy" . $e->getMessage());
            throw $e;
        }
    }

}