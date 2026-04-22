<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classe;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'type_profil' => 'required|in:admin,formateur,etudiant',
            'classe_id' => 'nullable|exists:classes,id',
            'matricule' => 'nullable|string|max:50',
        ]);

        $user = $this->service->create($data);
        
        // Handle Spatie Role
        $user->assignRole($data['type_profil']);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'type_profil' => 'required|in:admin,formateur,etudiant',
            'classe_id' => 'nullable|exists:classes,id',
            'matricule' => 'nullable|string|max:50',
        ]);

        $user = $this->service->update($user, $data);
        
        // Sync Spatie Role
        $user->syncRoles([$data['type_profil']]);

        return response()->json([
            'message' => 'Utilisateur mis à jour',
            'user' => $user
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->service->delete($user);
        return response()->json(['message' => 'Utilisateur supprimé']);
    }

    public function massAssignClass(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'classe_id' => 'required|exists:classes,id',
        ]);

        $this->service->assignClassToMultipleUsers($data['user_ids'], $data['classe_id']);

        return response()->json(['message' => 'Classe affectée en masse avec succès']);
    }
}
