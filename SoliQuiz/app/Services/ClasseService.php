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
            ->when($search, fn($q) => $q->where(function($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('promotion', 'like', "%{$search}%")
                    ->orWhereHas('formateur', function($subQuery) use ($search) {
                        $subQuery->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%");
                    });
            }))
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

    /**
     * Récupère les détails d'une classe avec ses statistiques et étudiants sans classe
     */
    public function getDetailsWithStats(int $id): array
    {
        $classe = Classe::with(['formateur', 'etudiants.tentatives'])->findOrFail($id);
        
        $totalEtudiants = $classe->etudiants->count();
        $activeStudents = $classe->etudiants->filter(fn($e) => $e->tentatives->count() > 0)->count();
        $tauxEngagement = $totalEtudiants > 0 ? round(($activeStudents / $totalEtudiants) * 100, 1) : 0;

        $allScores = $classe->etudiants->flatMap->tentatives->whereNotNull('score_obtenu')->pluck('score_obtenu');
        $moyenneGlobale = $allScores->count() > 0 ? round($allScores->avg(), 1) : null;
        
        $etudiantsSansClasse = resolve(UserService::class)->getAvailableStudents();

        return [
            'classe' => $classe,
            'tauxEngagement' => $tauxEngagement,
            'moyenneGlobale' => $moyenneGlobale,
            'etudiantsSansClasse' => $etudiantsSansClasse,
        ];
    }

    /**
     * Ajoute plusieurs étudiants à une classe
     */
    public function bulkAddStudents(Classe $classe, array $userIds): void
    {
        foreach ($userIds as $userId) {
            $this->addStudent($classe, $userId);
        }
    }

    /**
     * Importation en masse d'étudiants (création / affectation)
     */
    public function importStudents(Classe $classe, string $importData): array
    {
        $lines = explode("\n", $importData);
        $addedCount = 0;
        $createdCount = 0;
        $errors = [];
        $userService = resolve(UserService::class);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Parse formats: email OR firstname;lastname;email OR email;firstname;lastname
            $parts = preg_split('/[;,]/', $line);
            $parts = array_map('trim', $parts);

            $email = null;
            $nom = 'Étudiant';
            $prenom = 'Nouvel';

            if (count($parts) === 1) {
                $email = $parts[0];
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $prefix = explode('@', $email)[0];
                    $nameParts = explode('.', $prefix);
                    if (count($nameParts) >= 2) {
                        $prenom = ucfirst($nameParts[0]);
                        $nom = ucfirst($nameParts[1]);
                    } else {
                        $nom = ucfirst($prefix);
                        $prenom = 'Apprenant';
                    }
                }
            } elseif (count($parts) >= 3) {
                if (filter_var($parts[2], FILTER_VALIDATE_EMAIL)) {
                    $prenom = $parts[0];
                    $nom = $parts[1];
                    $email = $parts[2];
                } elseif (filter_var($parts[0], FILTER_VALIDATE_EMAIL)) {
                    $email = $parts[0];
                    $prenom = $parts[1];
                    $nom = $parts[2];
                }
            } elseif (count($parts) === 2) {
                if (filter_var($parts[1], FILTER_VALIDATE_EMAIL)) {
                    $email = $parts[1];
                    $nom = $parts[0];
                } elseif (filter_var($parts[0], FILTER_VALIDATE_EMAIL)) {
                    $email = $parts[0];
                    $nom = $parts[1];
                }
            }

            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Ligne invalide ou email incorrect : " . htmlspecialchars($line);
                continue;
            }

            $user = User::where('email', $email)->first();

            if ($user) {
                if ($user->type_profil === 'etudiant') {
                    $user->update(['classe_id' => $classe->id]);
                    $addedCount++;
                } else {
                    $errors[] = "L'utilisateur avec l'email {$email} existe déjà et n'est pas un étudiant (rôle: {$user->type_profil}).";
                }
            } else {
                $userService->create([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'password' => 'password',
                    'type_profil' => 'etudiant',
                    'classe_id' => $classe->id,
                ]);
                $createdCount++;
            }
        }

        return [
            'createdCount' => $createdCount,
            'addedCount' => $addedCount,
            'errors' => $errors,
        ];
    }
}
