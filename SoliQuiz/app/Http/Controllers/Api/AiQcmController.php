<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiQcmController extends Controller
{
    public function generateWithAI(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'question_count' => 'required|integer|min:1|max:100',
            'question_type' => 'required|string|in:single,multiple,both',
        ]);

        try {
            // 1. Try production webhook first
            $response = Http::timeout(120)->post('http://localhost:5678/webhook/generate-qcm', [
                'topic' => $request->input('topic'),
                'question_count' => $request->input('question_count'),
                'question_type' => $request->input('question_type'),
            ]);

            // 2. If it fails or returns 404 (because workflow is not active yet), fallback to test webhook
            if ($response->failed() || $response->status() === 404) {
                $response = Http::timeout(120)->post('http://localhost:5678/webhook-test/generate-qcm', [
                    'topic' => $request->input('topic'),
                    'question_count' => $request->input('question_count'),
                    'question_type' => $request->input('question_type'),
                ]);
            }

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Error generating questions from the AI.'], 500);
        } catch (\Exception $e) {
            // 3. Last resort fallback in case of connection refused/timeout on production port
            try {
                $response = Http::timeout(120)->post('http://localhost:5678/webhook-test/generate-qcm', [
                    'topic' => $request->input('topic'),
                    'question_count' => $request->input('question_count'),
                    'question_type' => $request->input('question_type'),
                ]);

                if ($response->successful()) {
                    return response()->json($response->json());
                }
            } catch (\Exception $subEx) {
                // Both connections failed
            }

            return response()->json(['error' => 'Could not connect to n8n. Make sure n8n is running on port 5678.'], 500);
        }
    }
}
