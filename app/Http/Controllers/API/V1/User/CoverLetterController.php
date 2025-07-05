<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\CoverLetterService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoverLetterController extends Controller
{
    public function __construct(protected CoverLetterService $coverLetterService)
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
        try {
            $coverLetter = $this->coverLetterService->show($id);
            return Helper::jsonResponse(true, 'Cover letter generated successfully', 200, $coverLetter);
        } catch (Exception $e) {
            Log::error("CoverLetterController::generate" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to generate cover letter ' . $e->getMessage(), 500);
        }
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
            'prompt_text' => 'required|string',
            'template_id' => 'required|integer|exists:templates,id',
        ]);

        try {
            $coverLetter = $this->coverLetterService->generate($validatedData);
            return Helper::jsonResponse(true, 'Cover letter generated successfully', 200, $coverLetter);
        } catch (Exception $e) {
            Log::error("CoverLetterController::generate" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to generate cover letter ' . $e->getMessage(), 500);
        }
    }
}
