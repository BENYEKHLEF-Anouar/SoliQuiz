<?php

namespace App\Services;

use App\Models\QCM;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class QcmService
{
    /**
     * Liste paginée des QCM avec filtrage optionnel (recherche, formateur)
     */
    public function paginate(int $perPage = 15, ?string $search = null, ?int $formateurId = null): LengthAwarePaginator
    {
        return QCM::with(['formateur', 'uniteApprentissage', 'classe.etudiants'])
            ->withCount(['questions', 'tentatives'])
            ->when($search, fn($q) => $q->where('titre', 'like', "%{$search}%"))
            ->when($formateurId, fn($q) => $q->where('formateur_id', $formateurId))
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Charge un QCM complet avec ses relations (questions, options, formateur)
     */
    public function findWithRelations(int $id): QCM
    {
        return QCM::with(['formateur', 'uniteApprentissage', 'questions.options'])->findOrFail($id);
    }

    /**
     * Crée un QCM et l'ensemble de ses questions/options via une transaction
     */
    public function create(array $data): QCM
    {
        return DB::transaction(function () use ($data) {
            $qcm = QCM::create([
                'formateur_id' => $data['formateur_id'],
                'unite_apprentissage_id' => $data['unite_apprentissage_id'] ?? null,
                'classe_id' => $data['classe_id'] ?? null,
                'titre' => $data['titre'],
                'duree_minutes' => $data['duree_minutes'],
                'score_reussite' => $data['score_reussite'] ?? 20,
                'statut' => $data['statut'] ?? 'brouillon',
            ]);

            if (!empty($data['questions'])) {
                $this->syncQuestions($qcm, $data['questions']);
            }

            if (!empty($data['competence_ids'])) {
                $qcm->competences()->sync($data['competence_ids']);
            }

            return $qcm->load(['questions.options', 'competences']);
        });
    }

    /**
     * Met à jour un QCM et recrée ses questions/options si fournies
     */
    public function update(QCM $qcm, array $data): QCM
    {
        return DB::transaction(function () use ($qcm, $data) {
            $qcm->update([
                'unite_apprentissage_id' => $data['unite_apprentissage_id'] ?? $qcm->unite_apprentissage_id,
                'classe_id' => $data['classe_id'] ?? $qcm->classe_id,
                'titre' => $data['titre'] ?? $qcm->titre,
                'duree_minutes' => $data['duree_minutes'] ?? $qcm->duree_minutes,
                'score_reussite' => $data['score_reussite'] ?? $qcm->score_reussite,
                'statut' => $data['statut'] ?? $qcm->statut,
            ]);

            if (isset($data['questions'])) {
                $qcm->questions()->delete();
                $this->syncQuestions($qcm, $data['questions']);
            }

            if (isset($data['competence_ids'])) {
                $qcm->competences()->sync($data['competence_ids']);
            }

            return $qcm->fresh(['questions.options', 'competences']);
        });
    }

    /**
     * Bascule l'état de publication d'un QCM
     */
    public function togglePublication(QCM $qcm): QCM
    {
        $newStatut = $qcm->statut === 'public' ? 'brouillon' : 'public';
        $qcm->update(['statut' => $newStatut]);
        return $qcm->fresh();
    }

    /**
     * Supprime définitivement un QCM et ses dépendances
     */
    public function delete(QCM $qcm): void
    {
        DB::transaction(fn() => $qcm->delete());
    }

    /**
     * Synchronise les questions et options d'un QCM en base de données
     */
    private function syncQuestions(QCM $qcm, array $questions): void
    {
        foreach ($questions as $qData) {
            $question = $qcm->questions()->create([
                'texte' => $qData['texte'],
                'type' => $qData['type'], // 'unique' | 'multiple'
                'points' => $qData['points'] ?? 1,
                'explication_feedback' => $qData['explication_feedback'] ?? null,
            ]);

            foreach ($qData['options'] ?? [] as $oData) {
                $question->options()->create([
                    'texte' => $oData['texte'],
                    'est_correcte' => $oData['est_correcte'] ?? false,
                    'feedback_specifique' => $oData['feedback_specifique'] ?? null,
                ]);
            }
        }
    }

    /**
     * Récupère les résultats globaux pour un formateur (Classes + QCMs avec tentatives)
     */
    public function getResultsForFormateur(int $formateurId): array
    {
        $formateur = \App\Models\User::findOrFail($formateurId);
        
        return [
            'classes' => $formateur->classeGeree()->with('etudiants')->get(),
            'qcms' => QCM::where('formateur_id', $formateurId)
                ->where('statut', '!=', 'brouillon')
                ->with(['tentatives.etudiant', 'uniteApprentissage'])
                ->latest()
                ->get()
        ];
    }

    /**
     * Vérifie manuellement si un QCM doit être fermé (tous les étudiants ont terminé)
     */
    public function checkAndAutoClose(QCM $qcm): bool
    {
        if ($qcm->statut !== 'public' || !$qcm->classe_id) {
            return false;
        }

        $totalStudents = $qcm->classe->etudiants()->count();
        
        if ($totalStudents === 0) {
            return false;
        }

        $completedStudents = $qcm->tentatives()
            ->whereIn('statut', ['reussi', 'echoue', 'abandonne'])
            ->distinct('etudiant_id')
            ->count('etudiant_id');

        if ($completedStudents >= $totalStudents) {
            $qcm->update(['statut' => 'termine']);
            return true;
        }

        return false;
    }

    /**
     * Ferme manuellement un QCM
     */
    public function closeQcm(QCM $qcm): QCM
    {
        $qcm->update(['statut' => 'termine']);
        return $qcm->fresh();
    }
}
