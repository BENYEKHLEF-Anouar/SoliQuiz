<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tentative;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiExplainController extends Controller
{
    public function explainQuestion(Request $request)
    {
        $request->validate([
            'question_id'  => 'required|integer|exists:questions,id',
            'tentative_id' => 'required|integer|exists:tentatives,id',
        ]);

        $question   = Question::with('options')->findOrFail($request->question_id);
        $tentative  = Tentative::with(['reponses.choixReponses'])->findOrFail($request->tentative_id);

        if ($tentative->etudiant_id !== auth()->id()) {
            return response()->json(['error' => 'Accès non autorisé.'], 403);
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
            'student_name'    => auth()->user()->prenom,
            'qcm_title'       => $tentative->qcm->titre ?? 'ce QCM',
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(120)->post('http://localhost:5678/webhook/ai-explain-question', $payload);

            if ($response->failed() || $response->status() === 404) {
                $response = \Illuminate\Support\Facades\Http::timeout(120)->post('http://localhost:5678/webhook-test/ai-explain-question', $payload);
            }

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['explanation'])) {
                    return response()->json(['explanation' => $data['explanation']]);
                }
            }

            return response()->json(['error' => 'Le moteur IA n\'a pas pu générer une explication.'], 500);

        } catch (\Exception $e) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(120)->post('http://localhost:5678/webhook-test/ai-explain-question', $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    if (!empty($data['explanation'])) {
                        return response()->json(['explanation' => $data['explanation']]);
                    }
                }
            } catch (\Exception $subEx) {
                // Ignore
            }

            Log::error('AI Explain Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Could not connect to n8n. Make sure n8n is running on port 5678.'], 500);
        }
    }
}
