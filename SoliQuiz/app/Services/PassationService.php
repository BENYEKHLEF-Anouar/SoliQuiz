<?php

namespace App\Services;

use App\Models\QCM;
use App\Models\Tentative;
use App\Models\Reponse;
use App\Models\ChoixReponse;
use App\Models\Option;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PassationService
{
    /**
     * Démarre une nouvelle tentative pour un étudiant sur un QCM donné.
     * Si une tentative "en_cours" existe déjà, on la retourne.
     */
    public function demarrer(User $etudiant, int $qcmId): Tentative
    {
        $existante = Tentative::where('etudiant_id', $etudiant->id)
            ->where('qcm_id', $qcmId)
            ->first();

        if ($existante) {
            return $existante->load('qcm.questions.options');
        }

        $tentative = Tentative::create([
            'etudiant_id' => $etudiant->id,
            'qcm_id' => $qcmId,
            'statut' => 'en_cours',
            'date_debut' => now(),
        ]);

        return $tentative->load('qcm.questions.options');
    }

    /**
     * Enregistre les réponses envoyées par l'étudiant pour une tentative.
     */
    public function enregistrerReponses(Tentative $tentative, array $answers): void
    {
        DB::transaction(function () use ($tentative, $answers) {
            foreach ($answers as $questionId => $optionIds) {
                if (!is_array($optionIds)) {
                    $optionIds = [$optionIds];
                }

                // Remove empty sentinel values (from unanswered multi-choice questions)
                $optionIds = array_filter($optionIds, fn($id) => $id !== '' && $id !== null);

                // Skip questions with no actual selection
                if (empty($optionIds)) {
                    continue;
                }

                $reponse = Reponse::updateOrCreate(
                    ['tentative_id' => $tentative->id, 'question_id' => $questionId],
                    ['repondu_a' => now()]
                );

                $reponse->choixReponses()->delete();

                foreach ($optionIds as $optId) {
                    ChoixReponse::create([
                        'reponse_id' => $reponse->id,
                        'option_id'  => $optId
                    ]);
                }
            }
        });
    }

    /**
     * Soumet la tentative et calcule le score global.
     */
    public function soumettre(Tentative $tentative): array
    {
        if ($tentative->statut !== 'en_cours') {
            return $this->buildResultat($tentative);
        }

        return DB::transaction(function () use ($tentative) {
            $qcm = $tentative->qcm->load('questions.options');
            $scoreTotal = 0;
            $scoreMax = 0;

            foreach ($qcm->questions as $question) {
                $scoreMax += $question->points;

                $reponse = $tentative->reponses()
                    ->with('choixReponses')
                    ->where('question_id', $question->id)
                    ->first();

                $optionsChoisiesIds = $reponse
                    ? $reponse->choixReponses->pluck('option_id')->toArray()
                    : [];

                $optionsCorrectes = $question->options
                    ->where('est_correcte', true)
                    ->pluck('id')
                    ->toArray();

                $estCorrect = $this->evaluerReponse(
                    $question->type,
                    $optionsChoisiesIds,
                    $optionsCorrectes
                );

                if ($estCorrect) {
                    $scoreTotal += $question->points;
                }
            }

            // Calculate score on 20-point scale
            $scoreSur20 = $scoreMax > 0 ? round(($scoreTotal / $scoreMax) * 20, 1) : 0;
            $reussi = $scoreSur20 >= $qcm->score_reussite;

            $tentative->update([
                'score_obtenu' => $scoreSur20,
                'statut' => $reussi ? 'reussi' : 'echoue',
                'date_fin' => now(),
            ]);

            // Auto-close logic
            if ($qcm->classe_id && $qcm->statut === 'public') {
                $totalStudents = \App\Models\User::where('classe_id', $qcm->classe_id)->count();
                if ($totalStudents > 0) {
                    $submittedStudentsCount = Tentative::where('qcm_id', $qcm->id)
                        ->whereIn('etudiant_id', function($query) use ($qcm) {
                            $query->select('id')->from('users')->where('classe_id', $qcm->classe_id);
                        })
                        ->where('statut', '!=', 'en_cours')
                        ->distinct('etudiant_id')
                        ->count('etudiant_id');

                    if ($submittedStudentsCount >= $totalStudents) {
                        $qcm->update(['statut' => 'termine']);
                    }
                }
            }

            return [
                'tentative' => $tentative->fresh(),
                'score_obtenu' => $scoreSur20,
                'score_max' => 20,
                'reussi' => $reussi,
            ];
        });
    }

    /**
     * Évalue si la réponse d'un étudiant est correcte.
     */
    private function evaluerReponse(string $type, array $choisies, array $correctes): bool
    {
        sort($choisies);
        sort($correctes);

        // Supports both legacy 'unique' and current 'choix_unique' format
        if ($type === 'choix_unique' || $type === 'unique') {
            return count($choisies) === 1 && $choisies[0] == $correctes[0];
        }

        // 'choix_multiple' or 'multiple': arrays must be identical
        return $choisies == $correctes;
    }

    private function buildResultat(Tentative $tentative): array
    {
        return [
            'tentative' => $tentative,
            'score_obtenu' => $tentative->score_obtenu ?? 0,
            'score_max' => 20,
            'reussi' => $tentative->statut === 'reussi',
        ];
    }

    public function getOrCreateTentativeState(User $student, int $qcmId): array
    {
        $qcm = QCM::findOrFail($qcmId);
        $tentative = $this->demarrer($student, $qcmId);

        if ($tentative->statut !== 'en_cours') {
            return ['status' => 'completed', 'message' => 'QCM déjà terminé'];
        }

        // Get existing answers if any
        $initialAnswers = [];
        $existingReponses = $tentative->reponses()->with(['choixReponses', 'question'])->get();
        foreach ($existingReponses as $reponse) {
            $options = $reponse->choixReponses->pluck('option_id')->toArray();
            $initialAnswers[$reponse->question_id] = $options;
        }

        // Calculate remaining seconds
        $debut = $tentative->date_debut;
        if ($qcm->duree_minutes > 0) {
            $finPrevue = $debut->copy()->addMinutes($qcm->duree_minutes);
            $tempsRestant = (int) now()->diffInSeconds($finPrevue, false);
            if ($tempsRestant <= 0) {
                $this->soumettre($tentative);
                return ['status' => 'completed', 'message' => 'Temps écoulé'];
            }
        } else {
            $tempsRestant = -1;
        }

        return [
            'status' => 'in_progress',
            'qcmId' => $qcm->id,
            'title' => $qcm->titre,
            'durationMinutes' => $qcm->duree_minutes,
            'tempsRestant' => $tempsRestant,
            'initialAnswers' => $initialAnswers,
        ];
    }
}
