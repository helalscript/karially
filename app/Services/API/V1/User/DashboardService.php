<?php

namespace App\Services\API\V1\User;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function dashboardData()
    {
        try {
            $data = [];

            try {
                $totalCareerGoals = $this->user->careerGoals()->count();
                $completedCareerGoals = $this->user->careerGoals()->where('status', 'Completed')->count();
                $completedPercentage = $totalCareerGoals > 0 ? round(($completedCareerGoals / $totalCareerGoals) * 100) : 0;
                
                $runningWeeklyTasks = $this->user->weeks()
                    ->where('status', 'in_progress')
                    ->whereNull('career_goal_id')
                    ->with('weeklyTasks:id,week_id,title,is_completed')
                    ->select('id', 'week_number', 'title', 'description', 'status', 'created_at')
                    ->withCount([
                        'weeklyTasks as total_tasks',
                        'weeklyTasks as completed_tasks' => function ($query) {
                            $query->where('is_completed', true);
                        },
                        'weeklyTasks as in_progress_tasks' => function ($query) {
                            $query->where('is_completed', false);
                        }
                    ])
                    ->get();

                $inProgressWeeklyTasksPercentage = $runningWeeklyTasks->sum('in_progress_tasks') > 0 ? round(($runningWeeklyTasks->sum('in_progress_tasks') / $runningWeeklyTasks->sum('total_tasks')) * 100) : 0;
                $completedTasksWeeklyTasksPercentage = $runningWeeklyTasks->sum('completed_tasks') > 0 ? round(($runningWeeklyTasks->sum('completed_tasks') / $runningWeeklyTasks->sum('total_tasks')) * 100) : 0;

                $data = [
                    'total_career_goals' => $totalCareerGoals,
                    'completed_career_goals' => $completedCareerGoals,
                    'completed_percentage' => $completedPercentage,
                    'running_weekly_tasks' => $runningWeeklyTasks,
                    'in_progress_weekly_tasks_percentage' => $inProgressWeeklyTasksPercentage,
                    'completed_weekly_tasks_percentage' => $completedTasksWeeklyTasksPercentage
                ];
            } catch (Exception $e) {
                Log::error("DashboardService::dashboardData - Error calculating dashboard data: " . $e->getMessage());
                throw $e;
            }
            return $data;
        } catch (Exception $e) {
            Log::error("DashboardService::dashboardData" . $e->getMessage());
            throw $e;

        }
    }



}