<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tentative;
use App\Models\QCM;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EtudiantService
{
    /**
     * Historique paginé des tentatives terminées d'un étudiant.
     */
    public function historique(User $etudiant, int $perPage = 10): LengthAwarePaginator
    {
        return Tentative::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['reussi', 'echoue', 'abandonne'])
            ->with(['qcm.uniteApprentissage'])
            ->latest('date_fin')
            ->paginate($perPage);
    }

    /**
     * Dashboard personnel : KPIs + progression par UA.
     */
    public function getDashboard(User $etudiant): array
    {
        $tentatives = Tentative::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['reussi', 'echoue'])
            ->with('qcm')
            ->get();

        $total = $tentatives->count();
        $reussis = $tentatives->where('statut', 'reussi')->count();

        return [
            'nb_tentatives' => $total,
            'nb_reussies' => $reussis,
            'taux_reussite' => $total > 0 ? round(($reussis / $total) * 100, 1) : 0,
            'score_moyen' => round($tentatives->avg('score_obtenu') ?? 0, 1),
            'meilleur_score' => $tentatives->max('score_obtenu') ?? 0,
            'derniere_activite' => $tentatives->sortByDesc('date_fin')->first()?->date_fin,
        ];
    }

    /**
     * Détail complet d'une tentative pour la page de résultat/feedback.
     */
    public function getTentativeDetail(User $etudiant, int $tentativeId): Tentative
    {
        return Tentative::where('etudiant_id', $etudiant->id)
            ->with([
                'qcm.questions.options',
                'reponses.choixReponses',
            ])
            ->findOrFail($tentativeId);
    }

}
