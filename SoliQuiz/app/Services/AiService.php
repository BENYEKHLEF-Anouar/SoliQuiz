<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Tentative;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    public function explainQuestion(int $questionId, int $tentativeId, int $userId, string $userName): string
    {
        $question   = Question::with('options')->findOrFail($questionId);
        $tentative  = Tentative::with(['reponses.choixReponses', 'qcm'])->findOrFail($tentativeId);

        if ($tentative->etudiant_id !== $userId) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Accès non autorisé.');
        }

        $reponse = $tentative->reponses->firstWhere('question_id', $question->id);
        $selectedOptionIds = $reponse
            ? $reponse->choixReponses->pluck('option_id')->toArray()
            : [];

        $allOptions = $question->options->map(fn($opt) => [
            'id'          => $opt->id,
            'texte'       => $opt->texte,
            'est_correcte' => (bool) $opt->est_correcte,
            'selected'    => in_array($opt->id, $selectedOptionIds),
        ])->values()->toArray();

        $correctOptions  = collect($allOptions)->where('est_correcte', true)->pluck('texte')->toArray();
        $selectedOptions = collect($allOptions)->where('selected', true)->pluck('texte')->toArray();

        $payload = [
            'question'        => $question->texte,
            'all_options'     => $allOptions,
            'correct_answers' => $correctOptions,
            'student_answers' => $selectedOptions,
            'explication'     => $question->explication_feedback ?? null,
            'student_name'    => $userName,
            'qcm_title'       => $tentative->qcm->titre ?? 'ce QCM',
        ];

        try {
            $response = Http::timeout(120)->post('http://localhost:5678/webhook/ai-explain-question', $payload);

            if ($response->failed() || $response->status() === 404) {
                $response = Http::timeout(120)->post('http://localhost:5678/webhook-test/ai-explain-question', $payload);
            }

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['explanation'])) {
                    return $data['explanation'];
                }
            }

            throw new \Exception('Le moteur IA n\'a pas pu générer une explication.');

        } catch (\Exception $e) {
            try {
                $response = Http::timeout(120)->post('http://localhost:5678/webhook-test/ai-explain-question', $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    if (!empty($data['explanation'])) {
                        return $data['explanation'];
                    }
                }
            } catch (\Exception $subEx) {
                // Ignore
            }

            Log::error('AI Explain Exception: ' . $e->getMessage());
            throw new \Exception('Could not connect to n8n. Make sure n8n is running on port 5678.');
        }
    }

    public function generateQcm(string $topic, int $questionCount, string $questionType): array
    {
        try {
            $response = Http::timeout(120)->post('http://localhost:5678/webhook/generate-qcm', [
                'topic' => $topic,
                'question_count' => $questionCount,
                'question_type' => $questionType,
            ]);

            if ($response->failed() || $response->status() === 404) {
                $response = Http::timeout(120)->post('http://localhost:5678/webhook-test/generate-qcm', [
                    'topic' => $topic,
                    'question_count' => $questionCount,
                    'question_type' => $questionType,
                ]);
            }

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Error generating questions from the AI.');
        } catch (\Exception $e) {
            try {
                $response = Http::timeout(120)->post('http://localhost:5678/webhook-test/generate-qcm', [
                    'topic' => $topic,
                    'question_count' => $questionCount,
                    'question_type' => $questionType,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $subEx) {
                // Ignore
            }

            throw new \Exception('Could not connect to n8n. Make sure n8n is running on port 5678.');
        }
    }

    public function chat(string $message, array $history, string $sessionId, string $ip, string $userAgent): array
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            throw new \Exception('Configuration IA incomplète.', 500);
        }

        Log::channel('chatbot')->info(json_encode([
            'session_id' => $sessionId,
            'speaker'    => 'user',
            'message'    => $message,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'timestamp'  => now()->toIso8601String(),
        ], JSON_UNESCAPED_UNICODE));

        $contents = [];
        foreach ($history as $msg) {
            $contents[] = [
                'role'  => $msg['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $msg['content']]],
            ];
        }
        $contents[] = ['role' => 'user', 'parts' => [['text' => $message]]];

        $systemInstruction =
            "You are SoliBot, the AI assistant of SoliQuiz, an interactive assessment platform created by Solicode. " .
            "SoliQuiz features: interactive QCM passation, progress tracking by Seance/UA/Competence, " .
            "a Formateur space with QCM bank and AI generation, and full cohort administration. " .
            "LANGUAGE RULES (critical): " .
            "- Detect the language of the user's message automatically. " .
            "- If the user writes in English, respond ONLY in English. " .
            "- If the user writes in French, respond ONLY in French. " .
            "- If the user writes in Darija (Moroccan Arabic dialect, whether in Arabic script or Latin letters like 'wach', 'kayen', '3lach', 'kifach', 'labas'), respond ONLY in Darija using the same script they used. " .
            "- Never mix languages in a single response. " .
            "Always be concise (max 3 sentences), warm, and professional. " .
            "NO emojis. If off-topic, politely redirect to SoliQuiz.";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        try {
            $startTime = microtime(true);

            $response = Http::withoutVerifying()
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(30)
                ->post($url, [
                    'contents'          => $contents,
                    'systemInstruction' => ['parts' => [['text' => $systemInstruction]]],
                ]);

            $responseTimeMs = (int) round((microtime(true) - $startTime) * 1000);

            if ($response->status() === 429) {
                Log::warning('Gemini quota exceeded.');
                throw new \Exception('Le quota journalier de l\'assistant IA est atteint. Reessayez demain.', 429);
            }

            if ($response->failed()) {
                Log::error('Gemini API Error [' . $response->status() . ']: ' . $response->body());
                throw new \Exception('Erreur du moteur IA.', 500);
            }

            $data = $response->json();
            $reply          = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Je n'ai pas pu traiter votre demande.";
            $detectedLang   = $data['candidates'][0]['content']['parts'][0]['detectedLanguage'] ?? null;

            Log::channel('chatbot')->info(json_encode([
                'session_id'        => $sessionId,
                'speaker'           => 'bot',
                'message'           => trim($reply),
                'detected_language' => $detectedLang,
                'ip_address'        => $ip,
                'user_agent'        => $userAgent,
                'response_time_ms'  => $responseTimeMs,
                'timestamp'         => now()->toIso8601String(),
            ], JSON_UNESCAPED_UNICODE));

            return [
                'reply'      => trim($reply),
                'session_id' => $sessionId,
            ];

        } catch (\Exception $e) {
            if ($e->getCode() === 429) {
                throw $e;
            }
            Log::error('Gemini Exception: ' . $e->getMessage());
            throw new \Exception('Une erreur imprévue est survenue.', 500);
        }
    }
}
