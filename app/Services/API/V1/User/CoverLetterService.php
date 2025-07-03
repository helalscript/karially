<?php

namespace App\Services\API\V1\User;

use App\Models\AiPrompt;
use App\Models\CoverLatter;
use App\Models\Template;
use App\Models\UserProfile;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CoverLetterService
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

        } catch (Exception $e) {
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
            $coverLetter = CoverLatter::where('id', $id)
                ->where('user_profile_id', $this->user->profile->id)
                ->firstOrFail();

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

        } catch (Exception $e) {
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
            // Logic to delete a specific resource
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

}