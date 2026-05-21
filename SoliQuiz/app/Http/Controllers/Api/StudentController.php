<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EtudiantService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $etudiantService;

    public function __construct(EtudiantService $etudiantService)
    {
        $this->etudiantService = $etudiantService;
    }

    /**
     * Get the profile of the authenticated student.
     */
    public function profile(Request $request)
    {
        $student = $request->user();
        if (!$student->isEtudiant()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json([
            'id' => $student->id,
            'nom' => $student->nom,
            'prenom' => $student->prenom,
            'email' => $student->email,
            'avatarUrl' => null, // Placeholder
            'role' => 'Apprenant',
            'cohort' => $student->classe->nom ?? 'N/A',
        ]);
    }

    public function scores(Request $request)
    {
        $student = $request->user();
        $data = $this->etudiantService->getApiScores($student);
        return response()->json($data);
    }

    public function evaluations(Request $request)
    {
        $student = $request->user();
        $evaluations = $this->etudiantService->getApiEvaluations($student);
        return response()->json($evaluations);
    }

    public function notifications()
    {
        // Hardcoded mock notifications since we don't have a notifications table
        return response()->json([
            [
                'id' => 1,
                'type' => 'urgent',
                'message' => 'Nouveau QCM disponible: PHP - Syntaxe Fondamentaux',
                'read' => false,
                'createdAt' => now()->subHours(2),
            ],
            [
                'id' => 2,
                'type' => 'grade',
                'message' => 'Votre note pour "Fondamentaux PHP" est disponible: 17/20',
                'read' => true,
                'createdAt' => now()->subDays(1),
            ],
        ]);
    }

    public function history(Request $request)
    {
        $student = $request->user();
        $history = $this->etudiantService->getApiHistory($student);
        return response()->json($history);
    }

    public function bibliotheque(Request $request)
    {
        $student = $request->user();
        $data = $this->etudiantService->getApiBibliotheque($student);
        return response()->json($data);
    }

    /**
     * Update the student's profile information.
     */
    public function updateProfile(Request $request)
    {
        $student = $request->user();
        if (!$student->isEtudiant()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
        ]);

        $student->nom = $request->nom;
        $student->prenom = $request->prenom;
        $student->save();

        return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'profile' => [
                'id' => $student->id,
                'nom' => $student->nom,
                'prenom' => $student->prenom,
                'email' => $student->email,
                'avatarUrl' => null,
                'role' => 'Apprenant',
                'cohort' => $student->classe->nom ?? 'N/A',
            ]
        ]);
    }

    /**
     * Update the student's password.
     */
    public function updatePassword(Request $request)
    {
        $student = $request->user();
        if (!$student->isEtudiant()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $student->password)) {
            return response()->json([
                'message' => 'Le mot de passe actuel est incorrect.',
                'errors' => [
                    'current_password' => ['Le mot de passe actuel est incorrect.']
                ]
            ], 422);
        }

        $student->password = $request->password;
        $student->save();

        return response()->json([
            'message' => 'Mot de passe mis à jour avec succès.'
        ]);
    }
}