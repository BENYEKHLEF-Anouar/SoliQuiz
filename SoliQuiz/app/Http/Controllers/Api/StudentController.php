<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tentative;
use App\Models\QCM;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudentController extends Controller
{
    // Hardcoded student ID for testing (user with ID 4 is Mehdi)
    private $studentId = 4;

    public function profile()
    {
        $student = User::where('type_profil', 'etudiant')->findOrFail($this->studentId);
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

    public function scores()
    {
        $student = User::findOrFail($this->studentId);
        // Compute global score average from tentatives where score_obtenu is not null
        $tentatives = $student->tentatives()->whereNotNull('score_obtenu')->get();
        $globalScore = $tentatives->avg('score_obtenu') ?? 0;
        // Compute ranking among students in same cohort
        $cohortId = $student->classe_id;
        $ranking = 0;
        if ($cohortId) {
            // Get all students in same cohort
            $cohortStudents = User::where('classe_id', $cohortId)->where('type_profil', 'etudiant')->get();
            $scores = $cohortStudents->map(function ($s) {
                $tentatives = $s->tentatives()->whereNotNull('score_obtenu')->get();
                return $tentatives->avg('score_obtenu') ?? 0;
            });
            $sorted = $scores->sortDesc()->values();
            $position = $sorted->search($globalScore);
            $ranking = $position !== false ? $position + 1 : 0;
        }
        return response()->json([
            'globalScore' => round($globalScore, 2),
            'ranking' => $ranking,
            'totalStudents' => $cohortId ? User::where('classe_id', $cohortId)->where('type_profil', 'etudiant')->count() : 0,
        ]);
    }

    public function evaluations()
    {
        $student = User::findOrFail($this->studentId);
        // Get published QCMs that the student has not attempted (or tentatives not completed)
        $attemptedQcmIds = $student->tentatives()->pluck('qcm_id');
        $pendingQcms = QCM::where('est_publie', true)
            ->whereNotIn('id', $attemptedQcmIds)
            ->get();
        $evaluations = $pendingQcms->map(function ($qcm) {
            return [
                'id' => $qcm->id,
                'title' => $qcm->titre,
                'subject' => $qcm->uniteApprentissage->nom ?? 'General',
                'dueDate' => null, // Not in schema
                'urgent' => false,
            ];
        });
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

    public function history()
    {
        $student = User::findOrFail($this->studentId);
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
}