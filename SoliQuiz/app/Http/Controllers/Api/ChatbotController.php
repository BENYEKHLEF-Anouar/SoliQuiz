<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message'    => 'required|string|max:2000',
            'history'    => 'nullable|array',
            'session_id' => 'nullable|string|max:64',
        ]);

        $message   = $request->input('message');
        $history   = $request->input('history', []);
        $sessionId = $request->input('session_id') ?: Str::uuid()->toString();
        $ip        = $request->ip();
        $ua        = substr($request->userAgent() ?? '', 0, 255);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'Configuration IA incomplète.'], 500);
        }

        Log::channel('chatbot')->info(json_encode([
            'session_id' => $sessionId,
            'speaker'    => 'user',
            'message'    => $message,
            'ip_address' => $ip,
            'user_agent' => $ua,
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

        $payload = json_encode([
            'contents'          => $contents,
            'systemInstruction' => ['parts' => [['text' => $systemInstruction]]],
        ]);

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        try {
            $startTime = microtime(true);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $body      = curl_exec($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            $responseTimeMs = (int) round((microtime(true) - $startTime) * 1000);

            if ($curlError) {
                Log::error('Gemini cURL Error: ' . $curlError);
                return response()->json(['error' => 'Erreur de connexion au moteur IA.'], 500);
            }

            $data = json_decode($body, true);

            if ($httpCode === 429) {
                Log::warning('Gemini quota exceeded.');
                return response()->json([
                    'error'      => 'Le quota journalier de l\'assistant IA est atteint. Reessayez demain.',
                    'session_id' => $sessionId,
                ], 429);
            }

            if ($httpCode !== 200 || isset($data['error'])) {
                Log::error('Gemini API Error [' . $httpCode . ']: ' . $body);
                return response()->json(['error' => 'Erreur du moteur IA.'], 500);
            }

            $reply          = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Je n'ai pas pu traiter votre demande.";
            $detectedLang   = $data['candidates'][0]['content']['parts'][0]['detectedLanguage'] ?? null;

            Log::channel('chatbot')->info(json_encode([
                'session_id'        => $sessionId,
                'speaker'           => 'bot',
                'message'           => trim($reply),
                'detected_language' => $detectedLang,
                'ip_address'        => $ip,
                'user_agent'        => $ua,
                'response_time_ms'  => $responseTimeMs,
                'timestamp'         => now()->toIso8601String(),
            ], JSON_UNESCAPED_UNICODE));

            return response()->json([
                'reply'      => trim($reply),
                'session_id' => $sessionId,
            ]);

        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur imprévue est survenue.'], 500);
        }
    }
}
