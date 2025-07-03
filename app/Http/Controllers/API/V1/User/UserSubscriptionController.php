<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\Subscription\UserSubscriptionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserSubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(UserSubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function myPlan(Request $request)
    {
        try {
            $data = $this->subscriptionService->myPlan();
            return Helper::jsonResponse(true, 'Subscription plan fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error('UserSubscriptionController::myPlan'.$e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch subscription plan', 500);
        }
    }
}
