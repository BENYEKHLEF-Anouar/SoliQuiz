<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
            ->paginate($perPage);
    }

    /**
     * Crée un nouvel utilisateur avec rôles et assignation de classe
     */
    public function create(array $data): User
    {
        $user = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'password' => $data['password'], // User model casts to hashed automatically
            'type_profil' => $data['type_profil'],
            'classe_id' => $data['classe_id'] ?? null,
            'matricule' => $data['matricule'] ?? null,
            'code_etudiant' => $data['code_etudiant'] ?? null,
        ]);
        // Assigner le rôle Spatie
        $spatieRole = $data['type_profil'] === 'etudiant' ? 'etudiant' : $data['type_profil'];
        $user->assignRole($spatieRole);

        // Si c'est un formateur et qu'une classe est fournie, on lie la classe au formateur
        if ($user->isFormateur() && !empty($data['classe_id'])) {
            \App\Models\Classe::where('id', $data['classe_id'])->update(['formateur_id' => $user->id]);
        }

        return $user;
    }

    /**
     * Met à jour les informations d'un utilisateur existant
     */
    public function update(User $user, array $data): User
    {
        // Remove password if empty (don't update)
        if (empty($data['password'] ?? '')) {
            unset($data['password']);
        }

        $user->update($data);

        // Synchroniser les rôles Spatie si le type_profil a changé
        if (isset($data['type_profil'])) {
            $spatieRole = $data['type_profil'] === 'etudiant' ? 'etudiant' : $data['type_profil'];
            $user->syncRoles([$spatieRole]);
        }

        // Si c'est un formateur et qu'une classe est fournie
        if ($user->isFormateur() && !empty($data['classe_id'])) {
            \App\Models\Classe::where('id', $data['classe_id'])->update(['formateur_id' => $user->id]);
        }

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
     * Recherche d'utilisateurs paginée avec chargement des relations de classe.
     */
    public function searchUsersWithRelations(?string $search = null, ?string $role = null): LengthAwarePaginator
    {
        return User::with(['classe', 'classeGeree'])
            ->when($search, function ($q) use ($search) {
                $q->where(function($sq) use ($search) {
                    $sq->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($q) use ($role) {
                $q->where('type_profil', $role);
            })
            ->orderByRaw("CASE WHEN type_profil = 'admin' THEN 1 WHEN type_profil = 'formateur' THEN 2 ELSE 3 END")
            ->orderBy('nom')
            ->paginate(8);
    }

    /**
     * Récupère la liste de tous les étudiants sans classe
     */
    public function getAvailableStudents(): Collection
    {
        return User::where('type_profil', 'etudiant')
            ->whereNull('classe_id')
            ->orderBy('nom')
            ->get();
    }
}
