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



}


// {
//                             \"plan_description\": \"Overall description of the career plan\",
//                             \"weeks\": [
//                                 {
//                                 \"week_number\": 1,
//                                 \"title\": \"Week title\",
//                                 \"goal\": \"Goal for the week\",
//                                 \"tasks\": [
//                                     {
//                                     \"title\": \"Task title\",
//                                     \"description\": \"Task description\",
//                                     \"due_date\": \"Optional due date (YYYY-MM-DD)\",
//                                     \"topics\": [
//                                         {
//                                         \"title\": \"Topic title\",
//                                         \"description\": \"Topic details\",
//                                         \"type\": \"theory | practical | tool\"
//                                         }
//                                     ]
//                                     }
//                                 ]
//                                 }
//                             ]
//                             }";