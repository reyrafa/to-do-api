<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiService
{
    public function generateTasks(string $input)
    {
        $geminiKey = config('app.gemini');
        $today = now()->toDateString();

        $text = "
        Today is {$today}.
        Convert the following text into tasks.
                Return ONLY valid JSON.

                Format:

                [
                {
                    \"title\": \"Task Name\",
                    \"due_date\": \"YYYY-MM-DD\"
                }
                ]

                Input:
                $input
            ";

        $response = Http::post('https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key=' . $geminiKey, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $text
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json'
            ]
        ]);

        $text = data_get(
            $response->json(),
            'candidates.0.content.parts.0.text'
        );
        $tasks = json_decode($text, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON returned from AI');
        }

        return $tasks;
    }

    public function generateTaskGroq(string $input)
    {
        $today = now()->format('Y-m-d l');
        $text = "
            Today is {$today}.

            Convert the input into tasks.

            Rules:
            - Use today's date as the reference.

            - Determine a priority for each task.
            - Priority can only be high, medium, or low.
            - High = urgent, important
            - Medium = important but not urgent
            - Low = optional or can be done later
            - Return ONLY valid JSON.

            Format:
            [
                {
                    \"title\": \"Task Name\",
                    \"due_date\": \"YYYY-MM-DD\",
                    \"priority\": \"high|medium|low\"
                }
            ]

            Input:
            $input
        ";

        $response = Http::withToken(config('app.groq'))
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $text
                    ]
                ]
            ]);
        $content = $response->json()['choices'][0]['message']['content'];
        $content = str_replace(['```json', '```'], '', $content);
        $content = trim($content);
        $tasks = json_decode($content, true);

        return $tasks;
    }
}