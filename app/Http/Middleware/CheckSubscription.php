<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use App\Models\Package;
use App\Models\Subcription;
use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $checkSubcriptionPakageActive = Package::where('status', 'active')->first();
        if (!$checkSubcriptionPakageActive) {
            return $next($request);
        }
        $user = Auth::user();
        // Check if the user has an active subscription
        $subscriptions = Subcription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('ended_at', '>=', now())
            ->get();
        if ($subscriptions->isEmpty()) {
            return Helper::jsonErrorResponse('you have no active subscription. please subscribe for use this feature', 403);
        }
        $hasActiveDays = false;
        // Check if the user has any active days
        foreach ($subscriptions as $sub) {
            if (Carbon::parse($sub->ended_at)->isFuture()) {
                $hasActiveDays = true;
                break;
            }
        }

        // If the user has no active days, redirect or abort
        if (!$hasActiveDays) {
            return Helper::jsonErrorResponse('Your subscription has expired. Please renew to continue.', 403);
        }

        return $next($request);
    }
}
