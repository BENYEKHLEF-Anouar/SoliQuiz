<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tentative;
use App\Models\QCM;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LeaderboardService
{
    /**
     * Calcule le classement général des étudiants.
     */
    public function getOverallRanking(?int $classeId = null, ?int $formateurId = null, ?int $perPage = null): mixed
    {
        $query = User::where('type_profil', 'etudiant')
            ->leftJoin('tentatives', function($join) {
                $join->on('users.id', '=', 'tentatives.etudiant_id')
                    ->where('tentatives.statut', '!=', 'en_cours');
            })
            ->select(
                'users.id',
                'users.nom',
                'users.prenom',
                'users.classe_id',
                DB::raw('COALESCE(AVG(tentatives.score_obtenu), 0) as average_score'),
                DB::raw('COUNT(tentatives.id) as completed_qcms'),
                DB::raw('COUNT(CASE WHEN tentatives.statut = "reussi" THEN 1 END) as successful_qcms')
            )
            ->groupBy('users.id', 'users.nom', 'users.prenom', 'users.classe_id');

        if ($classeId) {
            $query->where('users.classe_id', $classeId);
        } elseif ($formateurId) {
            $query->whereIn('users.classe_id', function($q) use ($formateurId) {
                $q->select('id')->from('classes')->where('formateur_id', $formateurId);
            });
        }

        $query->orderByDesc('average_score')
            ->orderByDesc('completed_qcms')
            ->with('classe');

        if ($perPage) {
            $students = $query->paginate($perPage);
            $rank = ($students->currentPage() - 1) * $students->perPage() + 1;
        } else {
            $students = $query->get();
            $rank = 1;
        }

        foreach ($students as $student) {
            $student->rank = $rank++;
            $student->success_rate = $student->completed_qcms > 0
                ? round(($student->successful_qcms / $student->completed_qcms) * 100)
                : 0;
            $student->average_score = round($student->average_score, 1);
        }

        return $students;
    }

    /**
     * Calcule le classement spécifique à un QCM.
     */
    public function getQcmRanking(int $qcmId, ?int $perPage = null): mixed
    {
        $query = Tentative::where('qcm_id', $qcmId)
            ->where('statut', '!=', 'en_cours')
            ->with(['etudiant.classe'])
            ->orderByDesc('score_obtenu')
            ->orderBy('date_fin');

        if ($perPage) {
            $tentatives = $query->paginate($perPage);
            $rank = ($tentatives->currentPage() - 1) * $tentatives->perPage() + 1;
        } else {
            $tentatives = $query->get();
            $rank = 1;
        }

        foreach ($tentatives as $tentative) {
            $tentative->rank = $rank++;
            $tentative->score_obtenu = round($tentative->score_obtenu, 1);
        }

        return $tentatives;
    }

    /**
     * Récupère la liste des QCMs éligibles pour le filtre de classement.
     */
    public function getQcmsForFilter(?int $classeId = null, ?int $formateurId = null): Collection
    {
        return QCM::query()
            ->where('statut', '!=', 'brouillon')
            ->when($classeId, fn($q) => $q->where('classe_id', $classeId))
            ->when($formateurId, fn($q) => $q->where('formateur_id', $formateurId))
            ->orderBy('titre')
            ->get();
    }

    /**
     * Récupère les podiums (top 3) pour un ensemble de QCMs.
     */
    public function getPodiumsForQcms(Collection $qcms): Collection
    {
        return $qcms->map(function ($qcm) {
            $allTentatives = Tentative::where('qcm_id', $qcm->id)
                ->where('statut', '!=', 'en_cours')
                ->with('etudiant.classe')
                ->orderByDesc('score_obtenu')
                ->orderBy('date_fin')
                ->get();

            $topTentatives = $allTentatives->take(3)->values()->map(function ($t, $index) {
                return [
                    'position' => $index + 1,
                    'etudiant_nom' => $t->etudiant->prenom . ' ' . substr($t->etudiant->nom, 0, 1) . '.',
                    'score' => $t->score_obtenu,
                ];
            });

            $totalStudents = 0;
            if ($qcm->classe_id) {
                $totalStudents = User::where('classe_id', $qcm->classe_id)->where('type_profil', 'etudiant')->count();
            } else {
                $totalStudents = User::where('type_profil', 'etudiant')->count();
            }

            return [
                'qcm_id' => $qcm->id,
                'qcm_titre' => $qcm->titre,
                'classe_nom' => $qcm->classe?->nom ?? 'Transverse',
                'total_students' => $totalStudents,
                'podium' => $topTentatives,
            ];
        })->filter(fn($item) => $item['podium']->isNotEmpty())->values();
    }
}

