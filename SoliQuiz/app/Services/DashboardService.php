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

        $tentativesStats = Tentative::whereNotNull('score_obtenu')
            ->selectRaw('COUNT(*) as total, AVG(score_obtenu) as moyenne')
            ->first();

        return [
            'nb_formateurs' => $counts['formateur'] ?? 0,
            'nb_etudiants' => $counts['etudiant'] ?? 0,
            'nb_classes' => Classe::count(),
            'nb_qcms_publie' => QCM::where('est_publie', true)->count(),
            'nb_tentatives' => $tentativesStats->total ?? 0,
            'score_moyen' => round($tentativesStats->moyenne ?? 0, 1),
        ];
    }

    /**
     * Retourne les indicateurs clés pour un formateur spécifique
     */
    public function getFormateurKpis(int $formateurId): array
    {
        $qcms = QCM::where('formateur_id', $formateurId)->pluck('id');
        
        $tentativesStats = Tentative::whereIn('qcm_id', $qcms)
            ->whereNotNull('score_obtenu')
            ->selectRaw('COUNT(*) as total, AVG(score_obtenu) as moyenne')
            ->first();

        return [
            'nb_qcms' => $qcms->count(),
            'nb_qcms_actifs' => QCM::where('formateur_id', $formateurId)->where('est_publie', true)->count(),
            'nb_tentatives' => $tentativesStats->total ?? 0,
            'score_moyen' => round($tentativesStats->moyenne ?? 0, 1),
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

}
