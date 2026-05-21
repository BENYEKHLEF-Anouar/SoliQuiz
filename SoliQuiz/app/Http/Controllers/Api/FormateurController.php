<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FormateurService;
use Illuminate\Http\Request;

class FormateurController extends Controller
{
    protected $formateurService;

    public function __construct(FormateurService $formateurService)
    {
        $this->formateurService = $formateurService;
    }

    /**
     * Get the profile of the authenticated formateur.
     */
    public function profile(Request $request)
    {
        $formateur = $request->user();
        if (!$formateur->isFormateur()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json([
            'id' => $formateur->id,
            'nom' => $formateur->nom,
            'prenom' => $formateur->prenom,
            'email' => $formateur->email,
            'avatarUrl' => null,
            'role' => 'Formateur Référent',
        ]);
    }

    public function qcms(Request $request)
    {
        $qcms = $this->formateurService->getQcms($request->user());
        return response()->json($qcms);
    }

    public function cohorts(Request $request)
    {
        $cohorts = $this->formateurService->getCohorts($request->user());
        return response()->json($cohorts);
    }

    public function cohortStudents($cohortId)
    {
        $students = $this->formateurService->getCohortStudents((int) $cohortId);
        return response()->json($students);
    }

    public function studentPerformance($studentId)
    {
        $performance = $this->formateurService->getStudentPerformance((int) $studentId);
        return response()->json($performance);
    }

    public function studentHistory($studentId)
    {
        $history = $this->formateurService->getStudentHistory((int) $studentId);
        return response()->json($history);
    }

    public function qcmResults(Request $request, $qcmId)
    {
        $results = $this->formateurService->getQcmResults($request->user(), (int) $qcmId);
        return response()->json($results);
    }

    public function results(Request $request)
    {
        $formateur = $request->user();
        if (!$formateur->isFormateur()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $this->formateurService->getResultsDashboard($formateur);
        return response()->json($data);
    }

    /**
     * Update the formateur's profile information.
     */
    public function updateProfile(Request $request)
    {
        $formateur = $request->user();
        if (!$formateur->isFormateur()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
        ]);

        $profile = $this->formateurService->updateProfile($formateur, $request->only(['nom', 'prenom']));

        return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'profile' => $profile
        ]);
    }

    /**
     * Update the formateur's password.
     */
    public function updatePassword(Request $request)
    {
        $formateur = $request->user();
        if (!$formateur->isFormateur()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $this->formateurService->updatePassword($formateur, $request->only(['current_password', 'password']));

        return response()->json([
            'message' => 'Mot de passe mis à jour avec succès.'
        ]);
    }

    public function pedagogie(Request $request)
    {
        $formateur = $request->user();
        if (!$formateur->isFormateur() && !$formateur->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $creatorFilter = $request->input('creator') ? (int) $request->input('creator') : null;
        $data = $this->formateurService->getPedagogieData($formateur, $creatorFilter);

        return response()->json($data);
    }
}