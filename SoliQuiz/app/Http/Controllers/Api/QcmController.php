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
            $correctOptions = $question->options->where('est_correcte', true)->pluck('id');
            $isCorrect = $selectedOptions->diff($correctOptions)->isEmpty() && $correctOptions->diff($selectedOptions)->isEmpty();
            return [
                'id' => $question->id,
                'text' => $question->texte,
                'userAnswer' => $selectedOptions->toArray(),
                'correctAnswer' => $correctOptions->toArray(),
                'isCorrect' => $isCorrect,
                'explanation' => $question->explication_feedback,
            ];
        });
        $score = $tentative->score_obtenu;
        $percentage = round(($score / 20) * 100);
        $objectiveMet = $score >= $qcm->score_reussite;
        return response()->json([
            'qcmId' => $qcm->id,
            'title' => $qcm->titre,
            'score' => $score,
            'totalQuestions' => $totalQuestions,
            'percentage' => $percentage,
            'objectiveMet' => $objectiveMet,
            'questions' => $questionDetails,
        ]);
    }
}