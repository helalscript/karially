<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\DashboardService;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    /**
     * Display a listing of the resource.
     */
    public function dashboardData()
    {
        try {
            $totalCareerGoals = $this->dashboardService->dashboardData();
            return Helper::jsonResponse(true, 'Dashboard data retrieved successfully.', 200, $totalCareerGoals);
        } catch (Exception $e) {
            Log::error('Error retrieving dashboard data: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to retrieve dashboard data: ' . $e->getMessage(), 500);
        }
    }


}
