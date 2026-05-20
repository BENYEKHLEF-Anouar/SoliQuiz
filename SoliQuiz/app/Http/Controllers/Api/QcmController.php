<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QCM;
use App\Models\Question;
use App\Models\Tentative;
use Illuminate\Http\Request;

class QcmController extends Controller
{

    public function show($id)
    {
        $qcm = QCM::with('uniteApprentissage')->findOrFail($id);
        return response()->json([
            'id' => $qcm->id,
            'title' => $qcm->titre,
            'durationMinutes' => $qcm->duree_minutes,
            'totalQuestions' => $qcm->questions->count(),
            'successScore' => $qcm->score_reussite,
            'isPublished' => $qcm->statut === 'public',
        ]);
    }

    public function questions($id)
    {
        $qcm = QCM::findOrFail($id);
        $questions = $qcm->questions()->with('options')->get();
        $formatted = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'text' => $question->texte,
                'type' => $question->type,
                'points' => $question->points,
                'options' => $question->options->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'text' => $option->texte,
                        // 'isCorrect' => $option->est_correcte, // Only include if needed (maybe not for student)
                    ];
                }),
            ];
        });
        return response()->json($formatted);
    }

    public function result(Request $request, $id)
    {
        // Get the latest completed tentative for this QCM and student
        $tentative = Tentative::where('qcm_id', $id)
            ->where('etudiant_id', $request->user()->id)
            ->whereNotNull('score_obtenu')
            ->latest('date_fin')
            ->first();
        if (!$tentative) {
            return response()->json(['message' => 'No result found'], 404);
        }
        $qcm = QCM::findOrFail($id);
        $totalQuestions = $qcm->questions->count();
        // Fetch questions with user answers and correct answers
        $questions = $qcm->questions()->with(['options', 'reponses' => function ($query) use ($tentative) {
            $query->where('tentative_id', $tentative->id);
        }])->get();
        $questionDetails = $questions->map(function ($question) use ($tentative) {
            $userReponse = $question->reponses->first();
            $selectedOptions = $userReponse ? $userReponse->choixReponses->pluck('option_id') : [];
            $borderCorrect = $question->options->where('est_correcte', true)->pluck('id');
            $isCorrect = $selectedOptions->diff($borderCorrect)->isEmpty() && $borderCorrect->diff($selectedOptions)->isEmpty();
            return [
                'id' => $question->id,
                'text' => $question->texte,
                'points' => $question->points,
                'userAnswer' => $selectedOptions->toArray(),
                'correctAnswer' => $borderCorrect->toArray(),
                'isCorrect' => $isCorrect,
                'explanation' => $question->explication_feedback,
                'options' => $question->options->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'text' => $option->texte,
                        'isCorrect' => (bool)$option->est_correcte,
                        'specificFeedback' => $option->feedback_specifique,
                    ];
                })->toArray(),
            ];
        });
        $score = $tentative->score_obtenu;
        $maxScore = $qcm->questions()->sum('points') ?: ($qcm->questions()->count() * 2) ?: 20;
        $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100) : 0;
        $objectiveMet = $score >= $qcm->score_reussite;
        return response()->json([
            'qcmId' => $qcm->id,
            'title' => $qcm->titre,
            'score' => $score,
            'totalQuestions' => $totalQuestions,
            'maxScore' => $maxScore,
            'percentage' => $percentage,
            'objectiveMet' => $objectiveMet,
            'questions' => $questionDetails,
        ]);
    }

    public function start(Request $request, $id)
    {
        $qcm = QCM::findOrFail($id);
        $student = $request->user();
        
        $passationService = resolve(\App\Services\PassationService::class);
        $tentative = $passationService->demarrer($student, $qcm->id);
        
        if ($tentative->statut !== 'en_cours') {
            return response()->json(['message' => 'QCM déjà terminé', 'status' => 'completed'], 400);
        }
        
        // Get existing answers if any
        $initialAnswers = [];
        $existingReponses = $tentative->reponses()->with(['choixReponses', 'question'])->get();
        foreach ($existingReponses as $reponse) {
            $options = $reponse->choixReponses->pluck('option_id')->toArray();
            $initialAnswers[$reponse->question_id] = $options;
        }
        
        // Calculate remaining seconds
        $debut = $tentative->date_debut;
        if ($qcm->duree_minutes > 0) {
            $finPrevue = $debut->copy()->addMinutes($qcm->duree_minutes);
            $tempsRestant = (int) now()->diffInSeconds($finPrevue, false);
            if ($tempsRestant <= 0) {
                $passationService->soumettre($tentative);
                return response()->json(['message' => 'Temps écoulé', 'status' => 'completed'], 400);
            }
        } else {
            $tempsRestant = -1;
        }
        
        return response()->json([
            'qcmId' => $qcm->id,
            'title' => $qcm->titre,
            'durationMinutes' => $qcm->duree_minutes,
            'tempsRestant' => $tempsRestant,
            'initialAnswers' => $initialAnswers,
        ]);
    }

    public function submit(Request $request, $id)
    {
        $tentative = Tentative::where('etudiant_id', $request->user()->id)
            ->where('qcm_id', $id)
            ->where('statut', 'en_cours')
            ->firstOrFail();

        $passationService = resolve(\App\Services\PassationService::class);
        
        $answers = $request->input('answers', []);
        
        $passationService->enregistrerReponses($tentative, $answers);
        $passationService->soumettre($tentative);
        
        return response()->json([
            'success' => true,
            'score' => $tentative->score_obtenu,
        ]);
    }
}