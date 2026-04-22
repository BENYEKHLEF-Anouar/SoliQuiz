<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Liste paginée des utilisateurs avec filtres optionnels
     */
    public function paginate(int $perPage = 15, ?string $search = null, ?string $profil = null): LengthAwarePaginator
    {
        return User::query()
            ->when($search, fn($q) => $q->where('nom', 'like', "%{$search}%")
                ->orWhere('prenom', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->when($profil, fn($q) => $q->where('type_profil', $profil))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Crée un nouvel utilisateur (avec hachage du mot de passe)
     */
    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    /**
     * Met à jour les informations d'un utilisateur existant
     */
    public function update(User $user, array $data): User
    {
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return $user->fresh();
    }

    /**
     * Supprime un utilisateur de la base
     */
    public function delete(User $user): void
    {
        $user->delete();
    }

    /**
     * Retourne le décompte des utilisateurs groupés par profil
     */
    public function countByProfil(): array
    {
        return User::query()
            ->selectRaw('type_profil, COUNT(*) as total')
            ->groupBy('type_profil')
            ->pluck('total', 'type_profil')
            ->toArray();
    }

    /**
     * Récupère la liste de tous les formateurs
     */
    public function getFormateurs(): Collection
    {
        return User::where('type_profil', 'formateur')->orderBy('nom')->get();
    }

    /**
     * Récupère la liste de tous les étudiants
     */
    public function getEtudiants(): Collection
    {
        return User::where('type_profil', 'etudiant')->orderBy('nom')->get();
    }

    /**
     * Affecte une classe à plusieurs étudiants en masse
     */
    public function assignClassToMultipleUsers(array $userIds, int $classeId): void
    {
        User::whereIn('id', $userIds)
            ->where('type_profil', 'etudiant')
            ->update(['classe_id' => $classeId]);
    }
}
