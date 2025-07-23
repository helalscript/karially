<?php

namespace App\Services\API\V1\User;

use App\Models\CareerGoal;
use App\Models\CareerPlan;
use App\Models\TaskTopic;
use App\Models\Week;
use App\Models\WeeklyTask;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CareerGoalService
{
    protected $user;
    protected $aIGeneratorService;

    public function __construct(AIGeneratorService $aIGeneratorService)
    {
        $this->user = Auth::user();
        $this->aIGeneratorService = $aIGeneratorService;
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
            $careerGoals = CareerGoal::where('user_id', $this->user->id)
                ->withCount([
                    'weeks as total_weeks',
                    'weeks as completed_weeks' => function ($query) {
                        $query->where('status', 'completed');
                    },
                ])
                ->latest()
                ->paginate($perPage);
            // Map over each item to add percentage
            $careerGoals->getCollection()->transform(function ($goal) {
                $goal->completion_percentage = $goal->total_weeks > 0
                    ? round(($goal->completed_weeks / $goal->total_weeks) * 100)
                    : 0;
                return $goal;
            });
            return $careerGoals;
        } catch (Exception $e) {
            Log::error("CareerGoalService::index" . $e->getMessage());
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
        //Call AI
        $aiContent = $this->aIGeneratorService->generateRoadmap(
            $validatedData['roadmap_name'],
            $validatedData['prompt'],
            $validatedData['duration']
        );

        // Decode AI's JSON output
        $aiData = json_decode($aiContent, true);
        // $aiData = $aiContent;

        if (!$aiData || !isset($aiData['weeks'])) {
            return back()->withErrors(['Invalid response from AI. Please try again.']);
        }
        // dd($aiData);
        try {
            DB::beginTransaction();
            // Step 1: Create Career Goal
            $careerGoal = CareerGoal::create([
                'user_id' => auth()->id(),
                'roadmap_name' => $validatedData['roadmap_name'],
                'target_week' => $validatedData['duration'],
                'description' => $aiData['plan_description'],
                'status' => 'in_progress',
            ]);

            // Step 2: Loop through Weeks
            foreach ($aiData['weeks'] as $weekData) {
                $week = Week::create([
                    'user_id' => auth()->id(),
                    'career_goal_id' => $careerGoal->id,
                    'week_number' => $weekData['week_number'],
                    'title' => $weekData['title'],
                    'description' => $weekData['description'],
                    // 'start_date' => $weekData['start_date'],
                    // 'end_date' => $weekData['end_date'],
                    // 'status' => 'not_started',
                ]);
            }
            DB::commit();
            return $careerGoal;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("CareerGoalService::store" . $e->getMessage());
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
            $careerGoal = CareerGoal::with(['weeks:id,career_goal_id,week_number,title,description,status'])
                ->where('user_id', $this->user->id)
                ->find($id);
            if (!$careerGoal) {
                throw new Exception("Career goal not found");
            }
            return $careerGoal;
        } catch (Exception $e) {
            Log::error("CareerGoalService::show" . $e->getMessage());
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
            Log::error("CareerGoalService::update" . $e->getMessage());
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
            $careerGoal = CareerGoal::where('user_id', $this->user->id)->find($id);
            if (!$careerGoal) {
                throw new Exception("Career goal not found");
            }
            // Delete related weeks and tasks
            $careerGoal->weeks()->each(function ($week) {
                $week->weeklyTasks()->delete();
                $week->delete();
            });
            $careerGoal->delete();
            return true;
        } catch (Exception $e) {
            Log::error("CareerGoalService::destroy" . $e->getMessage());
            throw $e;
        }
    }
    public function markAsCompleted(int $id)
    {
        try {
            $careerGoal = CareerGoal::where('user_id', $this->user->id)->find($id);
            if (!$careerGoal) {
                throw new Exception("Career goal not found");
            }
            $careerGoal->status = 'completed';
            $careerGoal->save();
            return $careerGoal;
        } catch (Exception $e) {
            Log::error("CareerGoalService::markAsCompleted" . $e->getMessage());
            throw $e;
        }
    }

    public function markAsWeekComplete($weekId)
    {
        try {
            DB::beginTransaction();
            $careerGoalWeek = Week::with(['careerGoal'])->find($weekId);
            if (!$careerGoalWeek) {
                throw new Exception("Career goal not found");
            }
            if ($careerGoalWeek->status === 'completed') {
                throw new Exception("Week is already marked as completed");
            }
            $careerGoalWeek->status = 'completed';
            $careerGoalWeek->save();

            //check if all weeks are completed
            $careerGoal = CareerGoal::where('id', $careerGoalWeek->career_goal_id)
                ->withCount([
                    'weeks as total_weeks',
                    'weeks as completed_weeks' => function ($query) {
                        $query->where('status', 'completed');
                    },
                ])
                ->first();
            
            if ($careerGoal) {
                if ($careerGoal->total_weeks === $careerGoal->completed_weeks) {
                    $careerGoal->status = 'completed';
                    $careerGoal->save();
                }
            }

            DB::commit();
            return $careerGoalWeek;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("CareerGoalService::markAsWeekComplete" . $e->getMessage());
            throw $e;
        }
    }

}