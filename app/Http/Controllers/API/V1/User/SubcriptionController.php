<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\GetPackageResource;
use App\Services\API\V1\User\SubcriptionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pest\Console\Help;

class SubcriptionController extends Controller
{
    public function __construct(protected SubcriptionService $subscriptionService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function getPackages(Request $request)
    {
        $validatedData = $request->validate([
            'per_page' => 'integer|min:1|max:100',
            'billing_cycle' => 'in:monthly,yearly,lifetime',
        ]);
        try {
            $plans = $this->subscriptionService->getPackages($request);
            return Helper::jsonResponse(true, 'Plans fetched successfully.', 200, GetPackageResource::collection($plans), true);
        } catch (Exception $e) {
            Log::error("SubcriptionController::getPackages - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch plans.', 500, [$e->getMessage()]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function getSinglePackages($id)
    {
        try {
            $plans = $this->subscriptionService->getSinglePackages($id);
            return Helper::jsonResponse(true, 'Plans fetched successfully.', 200, new GetPackageResource($plans));
        } catch (\Exception $e) {
            Log::error("SubcriptionController::getPackages - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch plans.', 500, [$e->getMessage()]);
        }
    }

    public function getMySubscription()
    {
        try {
            $subscription = $this->subscriptionService->getMySubscription();
            return Helper::jsonResponse(true, 'Successfully fetched My Subscription', 200, $subscription);
        } catch (Exception $e) {
            Log::error('PaymentController::getMySubscription', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch my subscription'], 500);
        }
    }

    /**
     * Create a Stripe Checkout session for a plan and redirect to checkout page
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function createSubscription(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'success_url' => 'required|url',
            'cancel_url' => 'required|url',
        ]);

        try {
            $session = $this->subscriptionService->createSubscription($validatedData, $id);
            return response()->json([
                'payment_url' => $session->url,
                'message' => 'Checkout session created successfully.',
            ], 201);
        } catch (Exception $e) {
            Log::error('Stripe Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create payment session.'], 500);
        }
    }


    /**
     * Handle incoming Stripe webhook events.
     */
    public function handleWebhook(Request $request)
    {
        try {
            $event = $this->subscriptionService->handleWebhook($request);
            return response()->json(['status' => 'success', 'message' => 'Webhook handled successfully.'], 200);
        } catch (Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to handle webhook.' . $e->getMessage()], 400);
        }
    }


}
