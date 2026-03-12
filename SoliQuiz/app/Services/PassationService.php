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
            ->where('statut', 'en_cours')
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
            $corrections = [];

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

            $pourcentage = $scoreMax > 0 ? round(($scoreTotal / $scoreMax) * 100, 1) : 0;
            $reussi = $pourcentage >= $qcm->score_reussite;

            $tentative->update([
                'score_obtenu' => $pourcentage,
                'statut' => $reussi ? 'reussi' : 'echoue',
                'date_fin' => now(),
            ]);

            return [
                'tentative' => $tentative->fresh(),
                'score_obtenu' => $pourcentage,
                'score_max' => 100,
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

        if ($type === 'unique') {
            return count($choisies) === 1 && $choisies[0] === $correctes[0];
        }

        // type === 'multiple' : les deux tableaux doivent être identiques
        return $choisies === $correctes;
    }

    private function buildResultat(Tentative $tentative): array
    {
        return [
            'tentative' => $tentative,
            'score_obtenu' => $tentative->score_obtenu ?? 0,
            'score_max' => 100,
            'reussi' => $tentative->statut === 'reussi',
        ];
    }
}
