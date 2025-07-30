<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\CoachMatchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoachMatchController extends Controller
{

    protected $coachMatchService;
    public function __construct(CoachMatchService $coachMatchService)
    {
        $this->coachMatchService = $coachMatchService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $matches = $this->coachMatchService->index($request);
            return Helper::jsonResponse(true, 'Matches fetched successfully', 200, $matches, true);
        } catch (\Exception $e) {
            Log::error("CoachMatchController::index - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch matches', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'navigating_career' => 'required|string',
            'session_help' => 'required|string',
            'current_role' => 'nullable|string|max:255',
            'career_goal' => 'nullable|string|max:255',
            'preferred_time' => 'nullable|string|max:255',
            'additional_info' => 'nullable|string|max:1000',
        ]);
        try {
            $match = $this->coachMatchService->store($validatedData);
            return Helper::jsonResponse(true, 'Match created successfully', 200, $match);
        } catch (\Exception $e) {
            Log::error("CoachMatchController::store - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to create match', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $match = $this->coachMatchService->show($id);
            return Helper::jsonResponse(true, 'Match fetched successfully', 200, $match);
        } catch (\Exception $e) {
            Log::error("CoachMatchController::show - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch match', 500);
        }
    }
 
}
