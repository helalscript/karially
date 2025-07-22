<?php

namespace App\Services\API\V1\User;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AIGeneratorService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public static function generateRoadmap($roadmap_name, $prompt, $duration)
    {
        $systemMessage = "You are a helpful AI career planning assistant. 
                            Generate a career roadmap based on the user's goal. 
                            Structure the output in JSON to match this format:

                            {
                            \"plan_description\": \"Overall description of the career plan\",
                            \"weeks\": [
                                {
                                \"week_number\": 1,
                                \"title\": \"Week title\",
                                \"description\": \"description for the week task\",
                                }
                            ]
                            }";

        $userMessage = "Roadmap Name: {$roadmap_name}
                        Duration: {$duration} weeks dont skip any weeks
                        Goal: {$prompt}
                        Generate a roadmap following the format and duration.";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.key'),
        ])->timeout(90) // set timeout to 90 seconds
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => $systemMessage],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.7,
            ]);
        // dd($response->json());
        return $response->json()['choices'][0]['message']['content'];
    }

    public static function generateWeeklyTask($weekly_task_name, $prompt)
    {
        $systemMessage = "You are a helpful AI weekly task planning assistant. 
                            Generate a weekly task list based on the user's goal, and always provide the most effective structure to help them stay focused and manage their time efficiently.

                            If the user needs help with focus or time management:
                            - Break the goal into small, actionable, trackable tasks.
                            - Use prioritization strategies like the Eisenhower Matrix (urgent-important) or time-blocking where relevant.
                            - Keep the structure simple, clear, and motivational.

                            Structure the output in JSON to match this format:

                            {
                                \"weeks_description\": \"Overall description of the week's plan\",
                                \"tasks\": [
                                    {
                                        \"title\": \"Task title maximum 30 words\"
                                    }
                                ]
                            }";

        $userMessage = "Week Task Name: {$weekly_task_name}
                        Goal: {$prompt}
                        Generate a weekly_task following the format.";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.key'),
        ])->timeout(90)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemMessage],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'temperature' => 0.7,
                ]);

        return $response->json()['choices'][0]['message']['content'];
    }

    public function generateCVSummary($promptData, $file = null)
    {
        $systemMessage = "You are a helpful AI CV assistant.
                            Generate a professional and polished CV summary. Expand each section (intro, body, and conclusion) with clarity and detail. Use formal tone, avoid repetition, and make each section informative and compelling. Emphasize technical skills, relevant experience, and enthusiasm for the role.
                            Split the given job description or user input into 3 sections for a CV summary:

                            Respond ONLY in valid JSON format like this:
                            {
                            \"intro\": \"...\",
                            \"body\": \"...\",
                            \"conclusion\": \"...\"
                            }";

        $userMessage = "Based on this prompt, generate a well-structured CV summary:\n";
        $userMessage .= $promptData['prompt_text'];

        if ($file) {
            $fileText = $this->extractTextFromFile($file);
            $userMessage .= "\n\nAlso consider this file content:\n" . $fileText;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.key'),
        ])->timeout(90)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemMessage],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'temperature' => 0.7,
                ]);

        return json_decode($response->json()['choices'][0]['message']['content'], true);

    }


    protected function extractTextFromFile($file)
    {
        $extension = $file->getClientOriginalExtension();

        switch ($extension) {
            case 'txt':
                return file_get_contents($file->getRealPath());
            case 'pdf':
                return (new \Smalot\PdfParser\Parser())->parseFile($file->getRealPath())->getText();
            case 'doc':
            case 'docx':
                return \PhpOffice\PhpWord\IOFactory::load($file->getRealPath())->getSections()[0]->getElements()[0]->getText();
            default:
                return '';
        }
    }


    public function generateProfileSummary(string $promptText, $file = null)
    {
        $systemPrompt = "You are a professional resume writing assistant.
                            Based on the given background, generate a professional and polished resume profile summary.

                            Respond ONLY with the summary as plain text. Do NOT include any headings, formatting, or JSON.";

        // Combine prompt and file content (if any)
        $userPrompt = "User Background:\n" . $promptText;

        if ($file) {
            $userPrompt .= "\n\nAdditional Info from Uploaded CV:\n" . $this->extractTextFromFile($file);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.key'),
        ])->timeout(90)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.7,
                ]);

        return trim($response->json()['choices'][0]['message']['content'] ?? 'Summary not generated.');
    }


}
