<?php

namespace App\Services\API\V1\User\Subscription;

use App\Models\Subscription;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserSubscriptionService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function myPlan()
    {
        try {
            $subscription = Subscription::with('plan.features')
                ->where('user_id', $this->user->id)
                ->where('status', 'active')
                ->first();
            return $subscription;
        } catch (Exception $e) {
            Log::error('UserSubscriptionService::myPlan'.$e->getMessage());
            throw $e;
        }
    }
}
