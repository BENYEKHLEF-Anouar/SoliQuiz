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
        $lastScores = Tentative::where('etudiant_id', $student->id)
            ->whereIn('statut', ['reussi', 'echoue'])
            ->latest('date_fin')
            ->limit(7)
            ->pluck('score_obtenu')
            ->reverse()
            ->values()
            ->toArray();

        return response()->json([
            'globalScore' => round($globalScore, 2),
            'ranking' => $ranking,
            'totalStudents' => $cohortId ? User::where('classe_id', $cohortId)->where('type_profil', 'etudiant')->count() : 0,
            'lastScores' => $lastScores ?: [],
        ]);
    }

    public function evaluations(Request $request)
    {
        $student = $request->user();
        // Get published QCMs that the student has not attempted (or tentatives not completed)
        $attemptedQcmIds = $student->tentatives()->pluck('qcm_id');
        $pendingQcms = QCM::where('statut', 'public')
            ->whereNotIn('id', $attemptedQcmIds)
            ->get();
        $evaluations = $pendingQcms->map(function ($qcm) {
            return [
                'id' => $qcm->id,
                'title' => $qcm->titre,
                'subject' => optional($qcm->uniteApprentissage)->nom ?? 'General',
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

    public function history(Request $request)
    {
        $student = $request->user();
        $tentatives = $student->tentatives()
            ->whereNotNull('score_obtenu')
            ->with('qcm')
            ->orderBy('date_fin', 'desc')
            ->get();
        $history = $tentatives->map(function ($tentative) {
            return [
                'id' => $tentative->id,
                'qcm_id' => $tentative->qcm->id,
                'title' => $tentative->qcm->titre,
                'date' => $tentative->date_fin?->format('Y-m-d H:i'),
                'score' => $tentative->score_obtenu,
                'totalQuestions' => $tentative->qcm->questions->count(),
            ];
        });
        return response()->json($history);
    }

    public function bibliotheque(Request $request)
    {
        $student = $request->user()->load('classe.formateur');
        $formateur = $student->classe ? $student->classe->formateur : null;
        
        $publicQcmIds = QCM::where('statut', 'public')
            ->where(function($query) use ($student) {
                $query->whereNull('classe_id')
                      ->orWhere('classe_id', $student->classe_id);
            })
            ->pluck('id');

        $tentativeQcmIds = Tentative::where('etudiant_id', $student->id)->pluck('qcm_id');
        $allIds = $publicQcmIds->merge($tentativeQcmIds)->unique();

        $qcms = QCM::whereIn('id', $allIds)
            ->with(['formateur', 'uniteApprentissage'])
            ->withCount('questions')
            ->orderByDesc('created_at')
            ->get();
            
        $tentatives = Tentative::where('etudiant_id', $student->id)->get();
        
        $qcmList = $qcms->map(function ($qcm) use ($tentatives) {
            $tentative = $tentatives->where('qcm_id', $qcm->id)->first();
            return [
                'id' => $qcm->id,
                'titre' => $qcm->titre,
                'duree_minutes' => $qcm->duree_minutes,
                'questions_count' => $qcm->questions_count,
                'etat' => $tentative ? $tentative->statut : 'a_faire',
                'score' => $tentative ? $tentative->score_obtenu : null,
                'tentative_id' => $tentative ? $tentative->id : null,
                'date_fin' => $tentative ? ($tentative->date_fin ? $tentative->date_fin->format('d M Y') : null) : null,
                'unite_nom' => $qcm->uniteApprentissage ? $qcm->uniteApprentissage->nom : 'Évaluation transverse',
                'unite_id' => $qcm->unite_apprentissage_id,
                'formateur_nom' => $qcm->formateur ? $qcm->formateur->nom_complet : 'SoliQuiz',
            ];
        });

        $tentativesWithScore = $student->tentatives()->whereNotNull('score_obtenu')->get();
        $moyenne = $tentativesWithScore->avg('score_obtenu') ?? 0;
        $moyenne = round($moyenne, 1);

        $unites = \App\Models\UniteApprentissage::where(function ($query) use ($student) {
            $query->whereHas('qcms', function ($q) use ($student) {
                $q->where('statut', 'public')
                  ->where(function($sq) use ($student) {
                      $sq->whereNull('classe_id');
                      if ($student->classe_id) {
                          $sq->orWhere('classe_id', $student->classe_id);
                      }
                  });
            });

            if ($student->classe_id) {
                $classe = \App\Models\Classe::find($student->classe_id);
                if ($classe && $classe->formateur_id) {
                    $query->orWhere('user_id', $classe->formateur_id);
                }
            }
        })->get()->map(function($ua) {
            return [
                'id' => $ua->id,
                'nom' => $ua->nom
            ];
        });

        return response()->json([
            'qcms' => $qcmList,
            'unites' => $unites,
            'moyenne' => $moyenne,
            'formateur' => $formateur ? $formateur->nom_complet : null,
        ]);
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