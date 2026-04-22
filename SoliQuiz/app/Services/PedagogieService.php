<?php

namespace App\Services;

use App\Models\Seance;
use App\Models\UniteApprentissage;
use App\Models\Competence;
use Illuminate\Support\Collection;

class PedagogieService
{
    /**
     * Liste toutes les séances avec leurs UAs et compétences
     */
    public function getFullStructure(): Collection
    {
        return Seance::with(['unitesApprentissage.competences'])->latest()->get();
    }

    /**
     * Séances CRUD
     */
    public function createSeance(array $data): Seance
    {
        return Seance::create($data);
    }

    public function updateSeance(Seance $seance, array $data): Seance
    {
        $seance->update($data);
        return $seance->fresh();
    }

    public function deleteSeance(Seance $seance): void
    {
        $seance->delete();
    }

    /**
     * Unités d'Apprentissage CRUD
     */
    public function createUA(array $data): UniteApprentissage
    {
        return UniteApprentissage::create($data);
    }

    public function updateUA(UniteApprentissage $ua, array $data): UniteApprentissage
    {
        $ua->update($data);
        return $ua->fresh();
    }

    public function deleteUA(UniteApprentissage $ua): void
    {
        $ua->delete();
    }

    /**
     * Compétences CRUD
     */
    public function createCompetence(array $data): Competence
    {
        return Competence::create($data);
    }

    public function updateCompetence(Competence $competence, array $data): Competence
    {
        $competence->update($data);
        return $competence->fresh();
    }

    public function deleteCompetence(Competence $competence): void
    {
        $competence->delete();
    }
}
