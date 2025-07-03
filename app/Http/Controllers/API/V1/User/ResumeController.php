<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\ResumeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResumeController extends Controller
{

    public function __construct(protected ResumeService $resumeService)
    {
        // Middleware can be added here if needed
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function generate(Request $request)
    {
        $validatedData = $request->validate([
            'template_id' => 'required|exists:templates,id',
            'prompt_text' => 'required|string'
        ]);
        try {
            $resume = $this->resumeService->generate($validatedData);
            return Helper::jsonResponse(true, 'Resume generated successfully', 200, $resume);
        } catch (Exception $e) {
            Log::error("ResumeController::generate" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to generate resume ' . $e->getMessage(), 500);
        }

    }
}
