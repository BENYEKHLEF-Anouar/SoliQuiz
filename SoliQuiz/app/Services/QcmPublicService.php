<?php

namespace App\Services;

use App\Models\QCM;
use App\Models\User;
use Illuminate\Support\Collection;

class QcmPublicService
{
    /**
     * Retourne les QCM publiés accessibles à l'étudiant,
     * avec le nombre de tentatives qu'il a déjà effectuées.
     */
    public function getQcmsDisponibles(User $etudiant): Collection
    {
        return QCM::where('statut', 'public')
            ->where(function($query) use ($etudiant) {
                // Either has no class restriction or student is in the target class
                $query->whereNull('classe_id')
                      ->orWhere('classe_id', $etudiant->classe_id);
            })
            ->with(['formateur', 'uniteApprentissage'])
            ->withCount('questions')
            ->withCount([
                'tentatives as mes_tentatives_count' => fn($q) =>
                    $q->where('etudiant_id', $etudiant->id),
            ])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Retourne un QCM publié avec ses questions et options (sans révéler est_correcte).
     * Utilisé pour afficher l'interface de passation.
     */
    public function getQcmPourPassation(int $qcmId, ?int $etudiantClasseId = null): QCM
    {
        $qcm = QCM::where('statut', 'public')
            ->where(function($query) use ($etudiantClasseId) {
                $query->whereNull('classe_id')
                      ->orWhere('classe_id', $etudiantClasseId);
            })
            ->with([
                'questions' => fn($q) => $q->with([
                    'options' => fn($o) =>
                        $o->select('id', 'question_id', 'texte', 'feedback_specifique')
                ])
            ])
            ->findOrFail($qcmId);

        return $qcm;
    }
}
