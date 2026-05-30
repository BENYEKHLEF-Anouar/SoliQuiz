<?php

namespace App\Services;

use App\Models\Tentative;
use App\Models\QCM;
use Illuminate\Support\Facades\Auth;

class ResultatService
{
    /**
     * Récupère les détails d'une tentative individuelle pour l'exportation.
     */
    public function getTentativeDetails(Tentative $tentative): array
    {
        $qcm = $tentative->qcm;
        $questions = $qcm->questions()->with(['options', 'reponses' => function($q) use ($tentative) {
            $q->where('tentative_id', $tentative->id);
        }])->get();

        $questionDetails = $questions->map(function ($question) {
            $userReponse = $question->reponses->first();
            $selectedOptions = $userReponse ? $userReponse->choixReponses->pluck('option_id')->toArray() : [];
            $correctOptions = $question->options->where('est_correcte', true)->pluck('id')->toArray();
            
            $isCorrect = (count($correctOptions) === count($selectedOptions)) && empty(array_diff($correctOptions, $selectedOptions));

            return (object) [
                'id'         => $question->id,
                'texte'      => $question->texte,
                'points'     => $question->points,
                'explication' => $question->explication_feedback,
                'isCorrect'  => $isCorrect,
                'options'    => $question->options->map(function($opt) use ($selectedOptions) {
                    $opt->isSelected = in_array($opt->id, $selectedOptions);
                    return $opt;
                })
            ];
        });

        return compact('qcm', 'tentative', 'questionDetails');
    }

    /**
     * Récupère les tentatives filtrées pour une cohorte.
     */
    public function getCohorteResults(array $filters): \Illuminate\Database\Eloquent\Collection
    {
        $query = Tentative::with(['etudiant.classe', 'qcm'])
            ->whereHas('qcm', function($q) {
                if (!Auth::user()->isAdmin()) {
                    $q->where('formateur_id', Auth::id());
                }
            });

        if (!empty($filters['classe'])) {
            $query->whereHas('etudiant.classe', function($q) use ($filters) {
                $q->where('nom', $filters['classe']);
            });
        }

        if (!empty($filters['qcm_id'])) {
            $query->where('qcm_id', $filters['qcm_id']);
        }

        if (!empty($filters['etudiant_id'])) {
            $query->where('etudiant_id', $filters['etudiant_id']);
        }

        return $query->latest()->get();
    }
}
