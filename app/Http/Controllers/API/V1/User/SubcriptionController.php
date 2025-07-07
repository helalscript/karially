<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\GetPackageResource;
use App\Services\API\V1\User\SubcriptionService;
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
        } catch (\Exception $e) {
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


}
