<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\QCM;
use App\Models\Classe;
use App\Models\Tentative;
use Illuminate\Http\Request;

class FormateurController extends Controller
{
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
        $qcms = QCM::where('formateur_id', $request->user()->id)
            ->withCount('questions')
            ->withCount('tentatives')
            ->get();
        $formatted = $qcms->map(function ($qcm) {
            return [
                'id' => $qcm->id,
                'title' => $qcm->titre,
                'status' => $qcm->est_publie ? 'Actif' : 'Brouillon',
                'questionsCount' => $qcm->questions_count,
                'assignedCohort' => null, // Not in schema
                'resultsCount' => $qcm->tentatives_count,
            ];
        });
        return response()->json($formatted);
    }

    public function cohorts(Request $request)
    {
        $classes = Classe::where('formateur_id', $request->user()->id)->get();
        $formatted = $classes->map(function ($classe) {
            return [
                'id' => $classe->id,
                'name' => $classe->nom,
                'promotion' => $classe->promotion,
            ];
        });
        return response()->json($formatted);
    }

    public function cohortStudents($cohortId)
    {
        $students = User::where('classe_id', $cohortId)
            ->where('type_profil', 'etudiant')
            ->get();
        $formatted = $students->map(function ($student) {
            $tentatives = $student->tentatives()->whereNotNull('score_obtenu')->get();
            $averageScore = $tentatives->avg('score_obtenu') ?? 0;
            // Determine alert flag (simple threshold)
            $alert = $averageScore < 10; // example
            return [
                'id' => $student->id,
                'name' => $student->prenom . ' ' . $student->nom,
                'avatarUrl' => null,
                'averageScore' => round($averageScore, 2),
                'alert' => $alert,
            ];
        });
        return response()->json($formatted);
    }

    public function studentPerformance($studentId)
    {
        $student = User::where('type_profil', 'etudiant')->findOrFail($studentId);
        $tentatives = $student->tentatives()->whereNotNull('score_obtenu')->orderBy('date_fin', 'desc')->get();
        $lastQcmScore = $tentatives->first()?->score_obtenu;
        $totalAttempts = $tentatives->count();
        $averageScore = $tentatives->avg('score_obtenu') ?? 0;
        $participation = $totalAttempts > 0 ? 'Active' : 'Faible'; // simplistic
        $alert = $averageScore < 10;
        return response()->json([
            'studentId' => $student->id,
            'name' => $student->prenom . ' ' . $student->nom,
            'lastQcmScore' => $lastQcmScore,
            'participation' => $participation,
            'averageScore' => round($averageScore, 2),
            'alert' => $alert,
        ]);
    }

    public function studentHistory($studentId)
    {
        $student = User::where('type_profil', 'etudiant')->findOrFail($studentId);
        $tentatives = $student->tentatives()
            ->whereNotNull('score_obtenu')
            ->with('qcm')
            ->orderBy('date_fin', 'desc')
            ->get();
        $history = $tentatives->map(function ($tentative) {
            return [
                'id' => $tentative->id,
                'title' => $tentative->qcm->titre,
                'date' => $tentative->date_fin?->format('Y-m-d H:i'),
                'score' => $tentative->score_obtenu,
                'totalQuestions' => $tentative->qcm->questions->count(),
            ];
        });
        return response()->json($history);
    }

    public function qcmResults(Request $request, $qcmId)
    {
        $qcm = QCM::where('formateur_id', $request->user()->id)->findOrFail($qcmId);
        $tentatives = Tentative::where('qcm_id', $qcmId)
            ->whereNotNull('score_obtenu')
            ->with('user')
            ->orderBy('score_obtenu', 'desc')
            ->get();

        $formatted = $tentatives->map(function ($tentative) use ($qcm) {
            return [
                'id' => $tentative->id,
                'studentName' => $tentative->user ? $tentative->user->prenom . ' ' . $tentative->user->nom : 'Étudiant Inconnu',
                'score' => $tentative->score_obtenu,
                'totalQuestions' => tap($qcm->questions)->count(),
                'date' => $tentative->date_fin ? $tentative->date_fin->format('Y-m-d H:i') : null,
            ];
        });

        return response()->json([
            'qcmId' => $qcm->id,
            'title' => $qcm->titre,
            'results' => $formatted
        ]);
    }
}