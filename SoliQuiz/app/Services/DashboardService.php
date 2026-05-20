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
     * Retourne l'état de santé du système en temps réel
     */
    public function getSystemStatus(): array
    {
        // 1. État de la Base de Données
        try {
            DB::connection()->getPdo();
            $dbStatus = 'Connectée';
            $dbColor = 'text-emerald-400';
        } catch (\Exception $e) {
            $dbStatus = 'Erreur';
            $dbColor = 'text-rose-400';
        }

        // 2. File d'attente (Queues)
        // On vérifie si la table existe pour éviter une erreur si le driver n'est pas configuré
        $queueCount = 0;
        try {
            $queueCount = DB::table('jobs')->count();
        } catch (\Exception $e) {
            // Table non existante, on reste à 0
        }

        // 3. Dernière sauvegarde
        // On simule une vérification de backup (on pourrait chercher dans storage/app/backups)
        $lastBackup = "Planifiée";
        
        return [
            'db_status' => $dbStatus,
            'db_color' => $dbColor,
            'queue_count' => $queueCount,
            'last_backup' => $lastBackup,
            'is_operational' => $dbStatus === 'Connectée'
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
            ->with('qcm')
            ->get()
            ->filter(function ($t) {
                if (!$t->qcm) return false;
                $limitMinutes = $t->qcm->duree_minutes > 0 ? ($t->qcm->duree_minutes + 2) : 120; // 120 mins limit if unlimited
                return $t->date_debut && $t->date_debut->copy()->addMinutes($limitMinutes)->isAfter(now());
            })
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
     * Retourne les métriques détaillées pour les classes gérées par un formateur.
     */
    public function getTrainerClassesMetrics(User $formateur): Collection
    {
        $qcmIds = QCM::where('formateur_id', $formateur->id)->pluck('id');

        return $formateur->classeGeree()
            ->withCount('etudiants')
            ->get()
            ->map(function($classe) use ($qcmIds) {
                $tentatives = Tentative::whereIn('etudiant_id', $classe->etudiants->pluck('id'))
                    ->whereIn('qcm_id', $qcmIds)
                    ->where('statut', '!=', 'en_cours')
                    ->get();
                
                $classe->moyenne = round($tentatives->avg('score_obtenu') ?? 0, 1);
                $classe->nb_reussis = $tentatives->where('statut', 'reussi')->count();
                $classe->taux_reussite = $tentatives->count() > 0 
                    ? round(($classe->nb_reussis / $tentatives->count()) * 100) 
                    : 0;
                
                return $classe;
            });
    }

    /**
     * Calcule la croissance hebdomadaire de l'engagement (tentatives) pour un formateur.
     */
    private function getTrainerWeeklyGrowth(User $formateur): string
    {
        $qcmIds = QCM::where('formateur_id', $formateur->id)->pluck('id');
        
        $now = now();
        $thisWeekStart = $now->copy()->subDays(7);
        $lastWeekStart = $now->copy()->subDays(14);

        $thisWeekCount = Tentative::whereIn('qcm_id', $qcmIds)
            ->where('created_at', '>=', $thisWeekStart)
            ->count();

        $lastWeekCount = Tentative::whereIn('qcm_id', $qcmIds)
            ->where('created_at', '>=', $lastWeekStart)
            ->where('created_at', '<', $thisWeekStart)
            ->count();

        if ($lastWeekCount === 0) {
            return $thisWeekCount > 0 ? '+100%' : '0%';
        }

        $growth = round((($thisWeekCount - $lastWeekCount) / $lastWeekCount) * 100);
        return ($growth >= 0 ? '+' : '') . $growth . '%';
    }

    /**
     * Retourne les métriques détaillées pour toutes les classes (Admin).
     */
    public function getAllClassesMetrics(): Collection
    {
        return Classe::withCount('etudiants')
            ->get()
            ->map(function($classe) {
                $tentatives = Tentative::whereIn('etudiant_id', $classe->etudiants->pluck('id'))
                    ->where('statut', '!=', 'en_cours')
                    ->get();
                
                $classe->moyenne = round($tentatives->avg('score_obtenu') ?? 0, 1);
                $classe->nb_reussis = $tentatives->where('statut', 'reussi')->count();
                $classe->taux_reussite = $tentatives->count() > 0 
                    ? round(($classe->nb_reussis / $tentatives->count()) * 100) 
                    : 0;
                
                return $classe;
            });
    }
}
