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
    public function index(Request $request)
    {
        try {
            $resumes = $this->resumeService->index($request);
            return Helper::jsonResponse(true, 'Resume list fetched successfully', 200, $resumes, true);
        } catch (Exception $e) {
            Log::error("ResumeController::index" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch resume list ' . $e->getMessage(), 500);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            $resume = $this->resumeService->store($validatedData);
            return Helper::jsonResponse(true, 'Resume generated successfully', 200, $resume);
        } catch (Exception $e) {
            Log::error("ResumeController::store" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to generate resume ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $resume = $this->resumeService->show($id);
            return Helper::jsonResponse(true, 'Resume fetched successfully', 200, $resume);
        } catch (Exception $e) {
            Log::error("ResumeController::show" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch resume ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
            $resume = $this->resumeService->update($id, $validatedData);
            return Helper::jsonResponse(true, 'Resume updated successfully', 200, $resume);
        } catch (Exception $e) {
            Log::error("ResumeController::update" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to update resume ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->resumeService->destroy($id);
            return Helper::jsonResponse(true, 'Resume deleted successfully', 200);
        } catch (Exception $e) {
            Log::error("ResumeController::destroy" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to delete resume ' . $e->getMessage(), 500);
        }
    }


    public function generateResumeSummary(Request $request)
    {
        $validatedData = $request->validate([
            'prompt_text' => 'required|string',
            'file' => 'nullable|mimes:doc,docx,pdf,txt|max:2048',
        ]);

        try {
            $coverLetter = $this->resumeService->generateResumeSummary($validatedData);
            return Helper::jsonResponse(true, 'Resume summary generated successfully', 200, $coverLetter);
        } catch (Exception $e) {
            Log::error("CoverLetterController::generate" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to generate cover letter ' . $e->getMessage(), 500);
        }
    }
}
