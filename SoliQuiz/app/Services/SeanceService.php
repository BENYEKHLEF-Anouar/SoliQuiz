<?php

namespace App\Services;

use App\Models\Seance;
use App\Models\UniteApprentissage;
use App\Models\Competence;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SeanceService
{
    /**
     * Liste paginée des séances d'apprentissage
     */
    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return Seance::withCount('unitesApprentissage')
            ->when($search, fn($q) => $q->where('nom', 'like', "%{$search}%"))
            ->latest('date')
            ->paginate($perPage);
    }

    /**
     * Liste de toutes les séances triées par date
     */
    public function all(): Collection
    {
        return Seance::orderBy('date_debut', 'desc')->get();
    }

    /**
     * Crée une nouvelle séance
     */
    public function create(array $data): Seance
    {
        return Seance::create([
            'nom' => $data['nom'],
            'date_debut' => $data['date_debut'] ?? null,
            'date_fin' => $data['date_fin'] ?? null,
            'user_id' => $data['user_id'] ?? null,
        ]);
    }

    /**
     * Met à jour une séance existante
     */
    public function update(Seance $seance, array $data): Seance
    {
        $seance->update([
            'nom' => $data['nom'],
            'date_debut' => $data['date_debut'] ?? null,
            'date_fin' => $data['date_fin'] ?? null,
        ]);
        return $seance->fresh();
    }

    /**
     * Supprime une séance
     */
    public function delete(Seance $seance): void
    {
        $seance->delete();
    }

    /**
     * Liste les unités d'apprentissage liées à la séance, avec leurs compétences
     */
    public function getUnitesApprentissage(Seance $seance): Collection
    {
        return $seance->unitesApprentissage()->with('competences')->get();
    }

    /**
     * Ajoute une nouvelle unité d'apprentissage à une séance
     */
    public function addUniteApprentissage(Seance $seance, array $data): UniteApprentissage
    {
        return $seance->unitesApprentissage()->create([
            'nom' => $data['nom'],
            'code' => $data['code'],
            'user_id' => $data['user_id'] ?? null,
            'date_debut' => $data['date_debut'] ?? null,
            'date_fin' => $data['date_fin'] ?? null,
        ]);
    }
    /**
     * Supprime une unité d'apprentissage
     */
    public function deleteUniteApprentissage(UniteApprentissage $unite): void
    {
        $unite->delete();
    }

    /**
     * Ajoute une compétence spécifique à une unité d'apprentissage
     */
    public function addCompetence(UniteApprentissage $unite, array $data): Competence
    {
        return tap(new Competence($data), function ($competence) use ($unite) {
            $competence->unite_apprentissage_id = $unite->id;
            $competence->save();
        });
    }

    /**
     * Supprime une compétence
     */
    public function deleteCompetence(Competence $competence): void
    {
        $competence->delete();
    }
}
