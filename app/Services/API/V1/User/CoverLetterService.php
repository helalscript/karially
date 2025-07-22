<?php

namespace App\Services\API\V1\User;

use App\Models\AiPrompt;
use App\Models\CoverLatter;
use App\Models\Template;
use App\Models\UserProfile;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CoverLetterService
{
    protected $user;
    protected $aiGeneratorService;

    public function __construct(AIGeneratorService $aiGeneratorService)
    {
        $this->user = Auth::user();
        $this->aiGeneratorService = $aiGeneratorService;
    }
    /**
     * Fetch all resources.
     *
     * @return mixed
     */
    public function index($request)
    {
        try {
            $per_page = $request->get('per_page', 10);
            $coverLetters = CoverLatter::
                with('userProfile')
                ->latest()
                ->whereHas('userProfile', fn($q) =>
                    $q->where('user_id', $this->user->id))
                ->paginate($per_page);
            return $coverLetters;

        } catch (Exception $e) {
            Log::error("CoverLetterService::index" . $e->getMessage());
            throw $e;

        }
    }


    /**
     * Store a new resource.
     *
     * @param array $validatedData
     * @return mixed
     */
    public function store(array $validatedData)
    {
        try {
            DB::beginTransaction();
            $userProfile = UserProfile::create([
                'user_id' => $this->user->id,
                'full_name' => $validatedData['full_name'] ?? null,
                'job_title' => $validatedData['job_title'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'phone' => $validatedData['phone'] ?? null,
                'website' => $validatedData['website'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'summary' => $validatedData['summary'] ?? null,
                'education' => $validatedData['education'] ? $validatedData['education'] : null,
                'experience' => $validatedData['experience'] ? $validatedData['experience'] : null,
                'certifications' => $validatedData['certifications'] ? $validatedData['certifications'] : null,
                'languages' => $validatedData['languages'] ? $validatedData['languages'] : null,
                'skills' => $validatedData['skills'] ? $validatedData['skills'] : null,
                'projects' => $validatedData['projects'] ? $validatedData['projects'] : null,
                'awards' => $validatedData['awards'] ? $validatedData['awards'] : null,
                'social_links' => $validatedData['social_links'] ? $validatedData['social_links'] : null,
                'interests' => $validatedData['interests'] ? $validatedData['interests'] : null,

            ]);
            $coverLetter = CoverLatter::create([
                'user_profile_id' => $userProfile->id,
                'title' => $validatedData['title'],
                'template_name' => $validatedData['template_name']
            ]);
            DB::commit();
            return $coverLetter;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("CoverLetterService::store" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Display a specific resource.
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        try {
            $coverLetter = CoverLatter::with('userProfile')
                ->whereHas('userProfile', fn($q) =>
                    $q->where('user_id', $this->user->id))
                ->find($id);
            if (!$coverLetter) {
                throw new Exception("Cover letter not found");
            }
            return $coverLetter;
        } catch (Exception $e) {
            Log::error("CoverLetterService::show" . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Update a specific resource.
     *
     * @param int $id
     * @param array $validatedData
     * @return mixed
     */
    public function update(int $id, array $validatedData)
    {
        try {
            $coverLetter = CoverLatter::with('userProfile')->find($id);
            if (!$coverLetter) {
                throw new Exception("Cover letter not found");
            }
            DB::beginTransaction();
            $coverLetter->update([
                'title' => $validatedData['title'],
                'template_name' => $validatedData['template_name']
            ]);
            $coverLetter->userProfile()->update([
                'full_name' => $validatedData['full_name'] ?? null,
                'job_title' => $validatedData['job_title'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'phone' => $validatedData['phone'] ?? null,
                'website' => $validatedData['website'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'summary' => $validatedData['summary'] ?? null,
                'education' => $validatedData['education'] ? $validatedData['education'] : null,
                'experience' => $validatedData['experience'] ? $validatedData['experience'] : null,
                'certifications' => $validatedData['certifications'] ? $validatedData['certifications'] : null,
                'languages' => $validatedData['languages'] ? $validatedData['languages'] : null,
                'skills' => $validatedData['skills'] ? $validatedData['skills'] : null,
                'projects' => $validatedData['projects'] ? $validatedData['projects'] : null,
                'awards' => $validatedData['awards'] ? $validatedData['awards'] : null,
                'social_links' => $validatedData['social_links'] ? $validatedData['social_links'] : null,
                'interests' => $validatedData['interests'] ? $validatedData['interests'] : null,
            ]);
            DB::commit();
            return $coverLetter;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("CoverLetterService::update" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a specific resource.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        try {
            $coverLetter = CoverLatter::with('userProfile')->find($id);
            if (!$coverLetter) {
                throw new Exception("Cover letter not found");
            }
            $coverLetter->delete();
            return true;
        } catch (Exception $e) {
            Log::error("CoverLetterService::destroy" . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate a cover letter.
     *
     * @param array $validatedData
     * @return mixed
     */
    public function generate(array $validatedData)
    {
        try {
            $profile = UserProfile::where('user_id', $this->user->id)->firstOrFail();

            $prompt = AiPrompt::create([
                'user_profile_id' => $profile->id,
                'prompt_text' => $validatedData['prompt_text'],
                'used_for' => 'cover_letter'
            ]);

            $template = Template::findOrFail($validatedData['template_id']);

            $rendered = str_replace('{{full_name}}', $profile->full_name, $template->html_body);
            // add other placeholder replacements

            $letter = CoverLatter::create([
                'user_profile_id' => $profile->id,
                'template_id' => $template->id,
                'ai_prompt_id' => $prompt->id,
                'generated_html' => $rendered,
            ]);
            return $letter;
        } catch (Exception $e) {
            Log::error("CoverLetterService::generate" . $e->getMessage());
            throw $e;
        }
    }

    public function generateCVSummary(array $validatedData)
    {
        try {
            $summary = $this->aiGeneratorService->generateCVSummary($validatedData);
            return $summary;
        } catch (Exception $e) {
            Log::error("CoverLetterService::generateCVSummary" . $e->getMessage());
            throw $e;
        }
    }

}