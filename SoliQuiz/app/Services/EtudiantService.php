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
        $enCoursCount = Tentative::where('etudiant_id', $etudiant->id)
            ->where('statut', 'en_cours')
            ->count();

        return [
            'nb_tentatives' => $total,
            'nb_reussies' => $reussis,
            'nb_en_cours' => $enCoursCount,
            'taux_reussite' => $total > 0 ? round(($reussis / $total) * 100, 1) : 0,
            'score_moyen' => round($tentatives->avg('score_obtenu') ?? 0, 1),
            'meilleur_score' => $tentatives->max('score_obtenu') ?? 0,
            'derniere_activite' => $tentatives->sortByDesc('date_fin')->first()?->date_fin,
        ];
    }

    /**
     * Récupère les QCM en cours pour l'étudiant.
     */
    public function getActiveSessions(User $etudiant): Collection
    {
        return Tentative::where('etudiant_id', $etudiant->id)
            ->where('statut', 'en_cours')
            ->with(['qcm.uniteApprentissage'])
            ->latest()
            ->get();
    }

    /**
     * Récupère les derniers scores (pour le graphique).
     */
    public function getLastScores(User $etudiant, int $limit = 7): array
    {
        return Tentative::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['reussi', 'echoue'])
            ->latest('date_fin')
            ->limit($limit)
            ->pluck('score_obtenu')
            ->reverse()
            ->toArray();
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
    /**
     * Récupère les QCM à venir pour l'étudiant (ceux de sa classe non encore tentés).
     */
    public function getUpcomingQcms(User $etudiant, int $limit = 3): Collection
    {
        if (!$etudiant->classe_id) {
            return collect();
        }

        // QCM de sa classe, statut public, non encore tentés par cet étudiant
        return QCM::where('classe_id', $etudiant->classe_id)
            ->where('statut', 'public')
            ->whereDoesntHave('tentatives', function($q) use ($etudiant) {
                $q->where('etudiant_id', $etudiant->id);
            })
            ->with('uniteApprentissage')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Récupère la progression de l'étudiant par unité d'apprentissage (objectif).
     */
    public function getProgressByUa(User $etudiant): Collection
    {
        return Tentative::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['reussi', 'echoue'])
            ->with(['qcm.uniteApprentissage.seance'])
            ->get()
            ->groupBy(function ($tentative) {
                return $tentative->qcm->unite_apprentissage_id ?? 0;
            })
            ->map(function ($group) {
                $first = $group->first();
                $ua = $first->qcm->uniteApprentissage;
                $uaNom = $ua?->nom ?? 'Indépendant';
                $sessionNom = $ua?->seance?->nom;

                $avg = $group->avg('score_obtenu');
                $total = $group->count();
                $reussis = $group->where('statut', 'reussi')->count();
                return [
                    'ua_nom' => $uaNom,
                    'session_nom' => $sessionNom,
                    'score_moyen' => round($avg, 1),
                    'total_tentatives' => $total,
                    'taux_reussite' => $total > 0 ? round(($reussis / $total) * 100, 1) : 0,
                ];
            });
    }
}
