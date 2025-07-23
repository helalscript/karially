<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\CoverLatterResource;
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
    public function index(Request $request)
    {
        try {
            $coverLetters = $this->coverLetterService->index($request);
            return Helper::jsonResponse(true, 'Cover letter list fetched successfully', 200, CoverLatterResource::collection($coverLetters), true);
        } catch (Exception $e) {
            Log::error("CoverLetterController::index" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch cover letter list' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'template_name' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'website' => 'nullable|string',
            'address' => 'nullable|string',
            'summary' => 'required|array',
            'education' => 'nullable|array',
            'experience' => 'nullable|array',
            'certifications' => 'nullable|array',
            'languages' => 'nullable|array',
            'skills' => 'nullable|array',
            'projects' => 'nullable|array',
            'awards' => 'nullable|array',
            'social_links' => 'nullable|array',
            'interests' => 'nullable|array',
        ]);

        try {
            $coverLetter = $this->coverLetterService->store($validatedData);
            return Helper::jsonResponse(true, 'Cover letter generated successfully', 200, $coverLetter);
        } catch (Exception $e) {
            Log::error("CoverLetterController::generate" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to generate cover letter ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $coverLetter = $this->coverLetterService->show($id);
            return Helper::jsonResponse(true, 'Cover letter generated successfully', 200, CoverLatterResource::make($coverLetter));
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
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'template_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'website' => 'nullable|string',
            'address' => 'nullable|string',
            'summary' => 'nullable|array',
            'education' => 'nullable|array',
            'experience' => 'nullable|array',
            'certifications' => 'nullable|array',
            'languages' => 'nullable|array',
            'skills' => 'nullable|array',
            'projects' => 'nullable|array',
            'awards' => 'nullable|array',
            'social_links' => 'nullable|array',
            'interests' => 'nullable|array',
        ]);

        try {
            $coverLetter = $this->coverLetterService->update($id, $validatedData);
            return Helper::jsonResponse(true, 'Cover letter generated successfully', 200, $coverLetter);
        } catch (Exception $e) {
            Log::error("CoverLetterController::generate" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to generate cover letter ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->coverLetterService->destroy($id);
            return Helper::jsonResponse(true, 'Cover letter deleted successfully', 200);
        } catch (Exception $e) {
            Log::error("CoverLetterController::destroy" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to delete cover letter ' . $e->getMessage(), 500);
        }
    }

    public function generateCVSummary(Request $request)
    {
        $validatedData = $request->validate([
            'prompt_text' => 'required|string',
            'file' => 'nullable|mimes:doc,docx,pdf,txt|max:2048',
        ]);

        try {
            $coverLetter = $this->coverLetterService->generateCVSummary($validatedData);
            return Helper::jsonResponse(true, 'Cover letter generated successfully', 200, $coverLetter);
        } catch (Exception $e) {
            Log::error("CoverLetterController::generate" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to generate cover letter ' . $e->getMessage(), 500);
        }
    }
}
