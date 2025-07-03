<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function current()
    {
        try {
            $subscription = Subscription::with('plan.features')
                ->where('user_id', Auth::id())
                ->where('status', 'active')
                ->latest()
                ->first();

            if (!$subscription) {
                return Helper::jsonErrorResponse('Subscription not found', 404);
            }

            return Helper::jsonResponse(true, 'Subscription fetched successfully', 200, $subscription);
        } catch (Exception $e) {
            Log::error('SubscriptionController::current ' . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch subscription', 500);
        }
    }
}
