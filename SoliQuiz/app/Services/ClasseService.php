<?php

namespace App\Services;

use App\Models\Classe;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ClasseService
{
    /**
     * Liste paginée des classes avec leur formateur
     */
    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return Classe::with('formateur')
            ->when($search, fn($q) => $q->where('nom', 'like', "%{$search}%")
                ->orWhere('promotion', 'like', "%{$search}%"))
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Récupère toutes les classes sans pagination
     */
    public function all(): Collection
    {
        return Classe::with('formateur')->orderBy('nom')->get();
    }

    /**
     * Crée une nouvelle classe
     */
    public function create(array $data): Classe
    {
        return Classe::create($data);
    }

    /**
     * Met à jour les informations d'une classe
     */
    public function update(Classe $classe, array $data): Classe
    {
        $classe->update($data);
        return $classe->fresh('formateur');
    }

    /**
     * Supprime une classe logiciellement
     */
    public function delete(Classe $classe): void
    {
        $classe->delete();
    }

    /**
     * Liste les étudiants rattachés à une classe spécifique
     */
    public function getEtudiants(Classe $classe): Collection
    {
        return $classe->etudiants()->orderBy('nom')->get();
    }

    /**
     * Affecte ou modifie le formateur responsable d'une classe
     */
    public function assignFormateur(Classe $classe, int $formateurId): Classe
    {
        $classe->update(['formateur_id' => $formateurId]);
        return $classe->fresh('formateur');
    }

    /**
     * Ajoute un étudiant à la classe
     */
    public function addStudent(Classe $classe, int $etudiantId): void
    {
        User::where('id', $etudiantId)->update(['classe_id' => $classe->id]);
    }

    /**
     * Retire un étudiant de la classe
     */
    public function removeStudent(int $etudiantId): void
    {
        User::where('id', $etudiantId)->update(['classe_id' => null]);
    }

    /**
     * Renvoie les statistiques globales d'une classe
     */
    public function stats(Classe $classe): array
    {
        return [
            'nb_etudiants' => $classe->etudiants()->count(),
            'nb_tentatives' => \App\Models\Tentative::whereIn(
                'etudiant_id',
                $classe->etudiants()->pluck('id')
            )->count(),
        ];
    }
}
