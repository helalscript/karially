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
        //
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
            $week = Week::where('user_id', $this->user->id)
                ->whereNull('career_goal_id')
                ->where('id', $validatedData['week_id'])
                ->first();

            if (!$week) {
                throw new Exception("Week not found");
            }
            $week->weeklyTasks()->create([
                'title' => $validatedData['title']
            ]);
            return $week->fresh('weeklyTasks:id,week_id,title,is_completed');

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
            $week = WeeklyTask::select('id', 'week_id', 'title', 'status')
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
            $week = WeeklyTask::find($id);
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
            $week = WeeklyTask::find($id);
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

    /**
     * Mark a single weekly task as completed.
     *
     * @param int $weeklyTaskId
     * @return WeeklyTask
     * @throws Exception
     */
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