<?php

namespace App\Services\API\V1\User;

use App\Models\AiPrompt;
use App\Models\Resume;
use App\Models\Template;
use App\Models\UserProfile;
use Exception;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ResumeService
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
            $coverLetters = Resume::
                with('userProfile')
                ->latest()
                ->whereHas('userProfile', fn($q) =>
                    $q->where('user_id', $this->user->id))
                ->paginate($per_page);
            return $coverLetters;
        } catch (Exception $e) {
            Log::error("ResumeService::index" . $e->getMessage());
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

            if (isset($validatedData['image'])) {
                $imagePath = Helper::fileUpload($validatedData['image'], 'user_profile_image', getFileName($validatedData['image']));
                $validatedData['image'] = $imagePath;
            }

            $userProfile = UserProfile::create([
                'user_id' => $this->user->id,
                'full_name' => isset($validatedData['full_name']) ? $validatedData['full_name'] : null,
                'image' => isset($validatedData['image']) ? $validatedData['image'] : null,
                'job_title' => isset($validatedData['job_title']) ? $validatedData['job_title'] : null,
                'email' => isset($validatedData['email']) ? $validatedData['email'] : null,
                'phone' => isset($validatedData['phone']) ? $validatedData['phone'] : null,
                'website' => isset($validatedData['website']) ? $validatedData['website'] : null,
                'address' => isset($validatedData['address']) ? $validatedData['address'] : null,
                'summary' => isset($validatedData['summary']) ? $validatedData['summary'] : null,
                'education' => isset($validatedData['education']) ? $validatedData['education'] : null,
                'experience' => isset($validatedData['experience']) ? $validatedData['experience'] : null,
                'certifications' => isset($validatedData['certifications']) ? $validatedData['certifications'] : null,
                'languages' => isset($validatedData['languages']) ? $validatedData['languages'] : null,
                'skills' => isset($validatedData['skills']) ? $validatedData['skills'] : null,
                'projects' => isset($validatedData['projects']) ? $validatedData['projects'] : null,
                'awards' => isset($validatedData['awards']) ? $validatedData['awards'] : null,
                'social_links' => isset($validatedData['social_links']) ? $validatedData['social_links'] : null,
                'interests' => isset($validatedData['interests']) ? $validatedData['interests'] : null,
            ]);

            $resume = Resume::create([
                'user_profile_id' => $userProfile->id,
                'title' => $validatedData['title'],
                'template_name' => $validatedData['template_name'],
            ]);

            DB::commit();
            return $resume;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("ResumeService::store - " . $e->getMessage());
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
            $resume = Resume::with('userProfile')
                ->whereHas('userProfile', fn($q) =>
                    $q->where('user_id', $this->user->id))
                ->find($id);
            if (!$resume) {
                throw new Exception("Cover letter not found");
            }
            return $resume;
        } catch (Exception $e) {
            Log::error("ResumeService::show" . $e->getMessage());
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

            $resume = Resume::with('userProfile')->find($id);
            if (!$resume) {
                throw new Exception("Cover letter not found");
            }
            DB::beginTransaction();
            $resume->update([
                'title' => $validatedData['title'],
                'template_name' => $validatedData['template_name']
            ]);
            $resume->userProfile()->update([
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
            return $resume;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("ResumeService::update" . $e->getMessage());
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
            $resume = Resume::with('userProfile')->find($id);
            if (!$resume) {
                throw new Exception("Cover letter not found");
            }
            $resume->delete();
            return true;
        } catch (Exception $e) {
            Log::error("ResumeService::destroy" . $e->getMessage());
            throw $e;
        }
    }


    public function generateResumeSummary(array $validatedData)
    {
        try {
            $summary = $this->aiGeneratorService->generateResumeProfileSummary($validatedData['prompt_text'], $validatedData['file'] ?? null);
            return $summary;
        } catch (Exception $e) {
            Log::error("ResumeService::generateResumeProfileSummary" . $e->getMessage());
            throw $e;
        }
    }
}