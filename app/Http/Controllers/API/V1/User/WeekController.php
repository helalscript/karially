<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Services\API\V1\User\WeekService;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Exception;
use Illuminate\Support\Facades\Log;

class WeekController extends Controller
{
    public function __construct(protected WeekService $weekService)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $weeklyTasks = $this->weekService->index($request);
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
            $weeklyTask = $this->weekService->store($validatedData);
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
            $weeklyTask = $this->weekService->show($id);
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
            $weeklyTask = $this->weekService->update($id, $validatedData);
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
            $this->weekService->destroy($id);
            return Helper::jsonResponse(true, 'Weekly task deleted successfully.', 200);
        } catch (Exception $e) {
            Log::error('Error deleting weekly task: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to delete weekly task: ' . $e->getMessage(), 500);
        }
    }

    public function markAsCompleted(string $id)
    {
        try {
            $weeklyTask = $this->weekService->markAsCompleted($id);
            return Helper::jsonResponse(true, 'Weekly task marked as completed successfully.', 200, $weeklyTask);
        } catch (Exception $e) {
            Log::error('Error marking weekly task as completed: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'Failed to mark weekly task as completed: ' . $e->getMessage(), 500);
        }
    }

}
