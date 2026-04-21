<?php

namespace App\Services;

use App\Models\User;
use App\Models\QCM;
use App\Models\Tentative;
use App\Models\Classe;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Retourne les indicateurs clés de performance (KPI) pour l'administrateur
     */
    public function getKpis(): array
    {
        $counts = User::query()
            ->selectRaw('type_profil, COUNT(*) as total')
            ->groupBy('type_profil')
            ->pluck('total', 'type_profil');

        $tentativesStats = Tentative::where('statut', '!=', 'en_cours')
            ->selectRaw('COUNT(*) as total, AVG(score_obtenu) as moyenne')
            ->first();

        // Calcul du taux de réussite global (score >= score_reussite du QCM)
        // Pour simplifier on va dire score >= 10 ou utiliser la relation
        $nbSucces = Tentative::where('statut', 'reussi')->count();
        $tauxReussite = $tentativesStats->total > 0 ? round(($nbSucces / $tentativesStats->total) * 100) : 0;

        // Tendances indicatives basées sur les dates de création (30 derniers jours vs 30 précédents)
        $now = now();
        $lastMonthStart = $now->copy()->subDays(30);
        $prevMonthStart = $now->copy()->subDays(60);

        $newUsersLastMonth = User::where('created_at', '>=', $lastMonthStart)->count();
        $newUsersPrevMonth = User::where('created_at', '>=', $prevMonthStart)->where('created_at', '<', $lastMonthStart)->count();
        
        $trendUsers = $newUsersPrevMonth > 0 ? round((($newUsersLastMonth - $newUsersPrevMonth) / $newUsersPrevMonth) * 100) : 100;

        return [
            'nb_formateurs' => $counts['formateur'] ?? 0,
            'nb_etudiants' => $counts['etudiant'] ?? 0,
            'nb_classes' => Classe::count(),
            'nb_qcms_publie' => QCM::where('statut', 'public')->count(),
            'nb_tentatives' => $tentativesStats->total ?? 0,
            'score_moyen' => round($tentativesStats->moyenne ?? 0, 1),
            'taux_reussite' => $tauxReussite,
            'trend_users' => ($trendUsers >= 0 ? '+' : '') . $trendUsers . '%',
        ];
    }

    /**
     * Renvoie les QCM les plus actifs (triés par nombre de tentatives)
     */
    public function getTopQcms(int $limit = 5): Collection
    {
        return QCM::withCount('tentatives')
            ->orderByDesc('tentatives_count')
            ->limit($limit)
            ->with('formateur')
            ->get();
    }

    /**
     * Renvoie l'historique récent des dernières tentatives d'examen
     */
    public function getRecentTentatives(int $limit = 10): Collection
    {
        return Tentative::with(['etudiant', 'qcm'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Renvoie les étudiants les plus performants (meilleurs scores moyens)
     */
    public function getTopPerformers(int $limit = 3): Collection
    {
        return User::where('type_profil', 'etudiant')
            ->join('tentatives', 'users.id', '=', 'tentatives.etudiant_id')
            ->where('tentatives.statut', '!=', 'en_cours')
            ->select('users.*', DB::raw('AVG(tentatives.score_obtenu) as moyenne_score'))
            ->groupBy('users.id', 'users.nom', 'users.prenom', 'users.email', 'users.password', 'users.role', 'users.matricule', 'users.code_etudiant', 'users.classe_id', 'users.type_profil', 'users.derniere_connexion', 'users.created_at', 'users.updated_at', 'users.email_verified_at', 'users.remember_token')
            ->orderByDesc('moyenne_score')
            ->limit($limit)
            ->with('classe')
            ->get();
    }

    /**
     * Indicateurs pour le formateur spécifique.
     */
    public function getTrainerKpis(User $formateur): array
    {
        $qcms = QCM::where('formateur_id', $formateur->id)->get();
        $classes = Classe::where('formateur_id', $formateur->id)->with('etudiants')->get();
        
        $nbEtudiants = $classes->sum(fn($c) => $c->etudiants->count());
        $nbQcms = $qcms->count();
        $nbPublies = $qcms->where('statut', 'public')->count();

        $nbActiveAttempts = Tentative::whereIn('qcm_id', $qcms->pluck('id'))
            ->where('statut', 'en_cours')
            ->count();

        return [
            'nb_qcms' => $nbQcms,
            'nb_qcms_publies' => $nbPublies,
            'nb_classes' => $classes->count(),
            'nb_etudiants' => $nbEtudiants,
            'nb_tentatives_actives' => $nbActiveAttempts,
            'growth_suffix' => $this->getTrainerWeeklyGrowth($formateur)
        ];
    }

    /**
     * Calcule la croissance hebdo des QCMs créés.
     */
    public function getTrainerWeeklyGrowth(User $formateur): string
    {
        $count = QCM::where('formateur_id', $formateur->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        return $count > 0 ? "+{$count}" : "0";
    }
}
