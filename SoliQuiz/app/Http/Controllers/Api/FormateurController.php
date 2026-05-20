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
            ->with('uniteApprentissage')
            ->withCount('questions')
            ->withCount('tentatives')
            ->get();
        $formatted = $qcms->map(function ($qcm) {
            return [
                'id' => $qcm->id,
                'title' => $qcm->titre,
                'status' => $qcm->statut === 'public' ? 'Actif' : ($qcm->statut === 'termine' ? 'Terminé' : 'Brouillon'),
                'questionsCount' => $qcm->questions_count,
                'assignedCohort' => $qcm->classe->nom ?? 'Général',
                'resultsCount' => $qcm->tentatives_count,
                'unite_id' => $qcm->unite_apprentissage_id,
                'unite_nom' => $qcm->uniteApprentissage->nom ?? 'Indépendant',
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
            ->with('etudiant')
            ->orderBy('score_obtenu', 'desc')
            ->get();

        $totalQuestions = $qcm->questions()->count();
        $maxScore = $qcm->questions()->sum('points') ?: ($totalQuestions * 2); // Fallback to 2pts per question

        $formatted = $tentatives->map(function ($tentative) use ($totalQuestions, $maxScore) {
            return [
                'id' => $tentative->id,
                'studentName' => $tentative->etudiant ? $tentative->etudiant->prenom . ' ' . $tentative->etudiant->nom : 'Étudiant Inconnu',
                'score' => $tentative->score_obtenu,
                'totalQuestions' => $totalQuestions,
                'maxScore' => $maxScore,
                'date' => $tentative->date_fin ? $tentative->date_fin->format('Y-m-d H:i') : null,
            ];
        });

        return response()->json([
            'qcmId' => $qcm->id,
            'title' => $qcm->titre,
            'results' => $formatted
        ]);
    }

    public function results(Request $request)
    {
        $formateur = $request->user();
        if (!$formateur->isFormateur()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $qcms = QCM::where('formateur_id', $formateur->id)
            ->with(['tentatives' => function ($query) {
                $query->with('etudiant.classe')->orderBy('date_fin', 'desc');
            }])
            ->get();

        $classes = Classe::where('formateur_id', $formateur->id)->get();
        $qcmIds = $qcms->pluck('id');

        $formattedClasses = $classes->map(function ($classe) use ($qcmIds) {
            $studentIds = \App\Models\User::where('classe_id', $classe->id)
                ->where('type_profil', 'etudiant')
                ->pluck('id');

            $tentatives = Tentative::whereIn('etudiant_id', $studentIds)
                ->whereIn('qcm_id', $qcmIds)
                ->where('statut', '!=', 'en_cours')
                ->get();

            $avg = round($tentatives->avg('score_obtenu') ?? 0, 1);
            $nbReussis = $tentatives->where('statut', 'reussi')->count();
            $rate = $tentatives->count() > 0 
                ? round(($nbReussis / $tentatives->count()) * 100) 
                : 0;

            return [
                'id' => $classe->id,
                'nom' => $classe->nom,
                'moyenne' => $avg,
                'taux_reussite' => $rate,
            ];
        });

        $formattedQcms = $qcms->map(function ($qcm) {
            $tentatives = $qcm->tentatives->map(function ($t) {
                return [
                    'id' => $t->id,
                    'etudiant_nom' => $t->etudiant ? ($t->etudiant->prenom . ' ' . $t->etudiant->nom) : 'Inconnu',
                    'etudiant_classe' => $t->etudiant && $t->etudiant->classe ? $t->etudiant->classe->nom : 'Hors cohorte',
                    'score' => $t->score_obtenu,
                    'statut' => $t->statut,
                    'date' => $t->date_debut ? $t->date_debut->format('d M Y') : ($t->date_fin ? $t->date_fin->format('d M Y') : '-'),
                    'duree' => $t->date_debut && $t->date_fin ? $t->date_debut->diffForHumans($t->date_fin, true) : '-',
                ];
            });

            return [
                'id' => $qcm->id,
                'title' => $qcm->titre,
                'score_reussite' => $qcm->score_reussite,
                'tentatives' => $tentatives,
            ];
        });

        return response()->json([
            'qcms' => $formattedQcms,
            'classes' => $formattedClasses,
        ]);
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

        $formateur->nom = $request->nom;
        $formateur->prenom = $request->prenom;
        $formateur->save();

        return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'profile' => [
                'id' => $formateur->id,
                'nom' => $formateur->nom,
                'prenom' => $formateur->prenom,
                'email' => $formateur->email,
                'avatarUrl' => null,
                'role' => 'Formateur Référent',
            ]
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

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $formateur->password)) {
            return response()->json([
                'message' => 'Le mot de passe actuel est incorrect.',
                'errors' => [
                    'current_password' => ['Le mot de passe actuel est incorrect.']
                ]
            ], 422);
        }

        $formateur->password = $request->password;
        $formateur->save();

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

        $creatorFilter = $request->input('creator');

        $query = \App\Models\Seance::with(['unitesApprentissage.competences', 'user'])
            ->orderBy('date_debut', 'desc');

        if (!$formateur->isAdmin()) {
            $query->where('user_id', $formateur->id);
        } elseif ($creatorFilter) {
            $query->where('user_id', $creatorFilter);
        }

        $seances = $query->get();

        if (!$formateur->isAdmin()) {
            $creatorIds = [$formateur->id];
        } else {
            $creatorIds = \App\Models\Seance::whereNotNull('user_id')->distinct()->pluck('user_id');
        }
        $creators = \App\Models\User::whereIn('id', $creatorIds)->orderBy('nom')->get();

        $formattedSeances = $seances->map(function ($seance) {
            return [
                'id' => $seance->id,
                'nom' => $seance->nom,
                'date_debut' => $seance->date_debut ? $seance->date_debut->format('Y-m-d') : null,
                'date_fin' => $seance->date_fin ? $seance->date_fin->format('Y-m-d') : null,
                'user_id' => $seance->user_id,
                'creator_name' => $seance->user ? $seance->user->prenom . ' ' . $seance->user->nom : 'Inconnu',
                'creator_role' => $seance->user ? $seance->user->type_profil : 'formateur',
                'unites' => $seance->unitesApprentissage->map(function ($ua) {
                    return [
                        'id' => $ua->id,
                        'code' => $ua->code,
                        'nom' => $ua->nom,
                        'date_debut' => $ua->date_debut ? $ua->date_debut->format('Y-m-d') : null,
                        'date_fin' => $ua->date_fin ? $ua->date_fin->format('Y-m-d') : null,
                        'competences' => $ua->competences->map(function ($comp) {
                            return [
                                'id' => $comp->id,
                                'code' => $comp->code,
                                'libelle' => $comp->libelle,
                                'description' => $comp->description,
                            ];
                        }),
                    ];
                }),
            ];
        });

        $formattedCreators = $creators->map(function ($creator) {
            return [
                'id' => $creator->id,
                'nom' => $creator->nom,
                'prenom' => $creator->prenom,
                'type_profil' => $creator->type_profil,
            ];
        });

        return response()->json([
            'seances' => $formattedSeances,
            'creators' => $formattedCreators,
        ]);
    }
}