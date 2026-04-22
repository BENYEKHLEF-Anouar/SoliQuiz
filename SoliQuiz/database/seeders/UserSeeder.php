<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends CsvSeeder 
{
    public function run() 
    {
        $usersData = $this->parseCSV('users.csv');

        // Create roles if they don't exist
        $roles = ['admin', 'formateur', 'etudiant'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        foreach ($usersData as $userData) {
            // Update or create the user
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'nom' => $userData['nom'],
                    'prenom' => $userData['prenom'],
                    'password' => Hash::make($userData['password']), // Hash the readable password from CSV
                    'matricule' => $userData['matricule'] ?? null,
                    'code_etudiant' => $userData['code_etudiant'] ?? null,
                    'type_profil' => $userData['type_profil'],
                    'derniere_connexion' => $userData['derniere_connexion'] ?? null,
                ]
            );

            // Assign role
            $user->syncRoles([$userData['type_profil']]);
        }
    }
}
