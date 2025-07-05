<?php

namespace App\Http\Controllers\ApI\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Services\API\V1\User\CoachService;
use Illuminate\Http\Request;
use Log;

class CoachController extends Controller
{

    public function __construct(protected CoachService $coachService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
            'category_id' => 'nullable|exists:categories,id',
        ]);
        try {
            $coaches = $this->coachService->index($request);
            return Helper::jsonResponse(true, 'Coaches fetched successfully.', 200, $coaches, true);
        } catch (\Exception $e) {
            Log::error("CoachController::index - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch coaches.', 500, [$e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $coach = $this->coachService->show($id);
            return Helper::jsonResponse(true, 'Coach details fetched successfully.', 200, $coach);
        } catch (\Exception $e) {
            Log::error("CoachController::show - " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch coach details.', 500, [$e->getMessage()]);
        }
    }

}
