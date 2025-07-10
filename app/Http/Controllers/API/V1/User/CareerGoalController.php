<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\CareerGoalService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CareerGoalController extends Controller
{

    public function __construct(protected CareerGoalService $careerGoalService)
    {
        // Middleware can be applied here if needed
        // $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $careerGoals = $this->careerGoalService->index($request);
            return Helper::jsonResponse(true, 'Career goals retrieved successfully.', 200, $careerGoals, true);
        } catch (Exception $e) {
            Log::error('Error retrieving career goals: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to retrieve career goals: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'roadmap_name' => 'required|string|max:255',
            'prompt' => 'required|string|max:500', // Adjust max length as needed
            'duration' => 'required|integer|min:1|max:7', // Assuming duration is in weeks
        ]);

        try {
            $careerGoal = $this->careerGoalService->store($validatedData);
            return Helper::jsonResponse(true, 'Career goal and roadmap created successfully.', 201, $careerGoal);
        } catch (Exception $e) {
            Log::error('Error creating career goal and roadmap: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to create career goal and roadmap: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $careerGoal = $this->careerGoalService->show($id);
            return Helper::jsonResponse(true, 'Career goal retrieved successfully.', 200, $careerGoal);
        } catch (Exception $e) {
            Log::error('Error retrieving career goal: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to retrieve career goal: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $validatedData = $request->validate([
        //     'roadmap_name' => 'sometimes|required|string',
        //     'target_week' => 'sometimes|required|integer|min:1|max:10',
        //     'description' => 'sometimes|nullable|string',
        //     'status' => 'sometimes|required|in:not_started,in_progress,completed',
        // ]);

        // try {
        //     $careerGoal = $this->careerGoalService->update($id, $validatedData);
        //     return Helper::jsonResponse(true, 'Career goal updated successfully.', 200, $careerGoal);
        // } catch (Exception $e) {
        //     Log::error('Error updating career goal: ' . $e->getMessage());
        //     return Helper::jsonResponse(false, 'Failed to update career goal: ' . $e->getMessage(), 500);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->careerGoalService->destroy($id);
            return Helper::jsonResponse(true, 'Career goal deleted successfully.', 200);
        } catch (Exception $e) {
            Log::error('Error deleting career goal: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to delete career goal: ' . $e->getMessage(), 500);
        }
    }
}
