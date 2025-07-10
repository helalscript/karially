<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\WeeklyTaskService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeeklyTaskController extends Controller
{

    public function __construct(protected WeeklyTaskService $weeklyTaskService)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $weeklyTasks = $this->weeklyTaskService->index($request);
            return Helper::jsonResponse(true, 'Weekly tasks retrieved successfully.', 200, $weeklyTasks);
        } catch (Exception $e) {
            Log::error('Error retrieving weekly tasks: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to retrieve weekly tasks: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // 'week_number' => 'required|integer|min:1|max:52|unique:weeks,week_number,NULL,id,user_id,' . auth()->id(),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $weeklyTask = $this->weeklyTaskService->store($validatedData);
            return Helper::jsonResponse(true, 'Weekly task created successfully.', 201, $weeklyTask);
        } catch (Exception $e) {
            Log::error('Error creating weekly task: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to create weekly task: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $weeklyTask = $this->weeklyTaskService->show($id);
            return Helper::jsonResponse(true, 'Weekly task retrieved successfully.', 200, $weeklyTask);
        } catch (Exception $e) {
            Log::error('Error retrieving weekly task: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to retrieve weekly task: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $weeklyTask = $this->weeklyTaskService->update($id, $validatedData);
            return Helper::jsonResponse(true, 'Weekly task updated successfully.', 200, $weeklyTask);
        } catch (Exception $e) {
            Log::error('Error updating weekly task: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to update weekly task: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->weeklyTaskService->destroy($id);
            return Helper::jsonResponse(true, 'Weekly task deleted successfully.', 200);
        } catch (Exception $e) {
            Log::error('Error deleting weekly task: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to delete weekly task: ' . $e->getMessage(), 500);
        }
    }
}
