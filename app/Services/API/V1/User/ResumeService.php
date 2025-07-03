<?php

namespace App\Services\API\V1\User;

use App\Models\AiPrompt;
use App\Models\Resume;
use App\Models\Template;
use App\Models\UserProfile;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ResumeService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
    /**
     * Fetch all resources.
     *
     * @return mixed
     */
    public function index($request)
    {
        try {

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

        } catch (Exception $e) {
            Log::error("ResumeService::store" . $e->getMessage());
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

        } catch (Exception $e) {
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
            // Logic to delete a specific resource
        } catch (Exception $e) {
            Log::error("ResumeService::destroy" . $e->getMessage());
            throw $e;
        }
    }

    public function generate(array $validatedData)
    {
        try {
            $profile = UserProfile::where('user_id', $this->user->id)->firstOrFail();

            // Save AI prompt
            $prompt = AiPrompt::create([
                'user_profile_id' => $profile->id,
                'prompt_text' => $validatedData['prompt_text'],
                'used_for' => 'resume'
            ]);

            // Fetch template
            $template = Template::findOrFail($validatedData['template_id']);

            // Render HTML (basic)
            $rendered = str_replace('{{full_name}}', $profile->full_name, $template->html_body);
            // Add more dynamic replacements...

            $resume = Resume::create([
                'user_profile_id' => $profile->id,
                'template_id' => $template->id,
                'ai_prompt_id' => $prompt->id,
                'generated_html' => $rendered,
            ]);

            return $resume;
        } catch (Exception $e) {
            Log::error("ResumeService::generate" . $e->getMessage());
            throw $e;
        }
    }
}