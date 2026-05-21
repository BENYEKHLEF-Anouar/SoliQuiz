<?php

namespace App\Services;

use App\Models\User;
use App\Models\QCM;
use App\Models\Classe;
use App\Models\Tentative;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FormateurService
{
    public function getQcms(User $formateur): array
    {
        $qcms = QCM::where('formateur_id', $formateur->id)
            ->with('uniteApprentissage')
            ->withCount('questions')
            ->withCount('tentatives')
            ->get();
            
        return $qcms->map(function ($qcm) {
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
        })->toArray();
    }

    public function getCohorts(User $formateur): array
    {
        $classes = Classe::where('formateur_id', $formateur->id)->get();
        return $classes->map(function ($classe) {
            return [
                'id' => $classe->id,
                'name' => $classe->nom,
                'promotion' => $classe->promotion,
            ];
        })->toArray();
    }

    public function getCohortStudents(int $cohortId): array
    {
        $students = User::where('classe_id', $cohortId)
            ->where('type_profil', 'etudiant')
            ->get();
            
        return $students->map(function ($student) {
            $tentatives = $student->tentatives()->whereNotNull('score_obtenu')->get();
            $averageScore = $tentatives->avg('score_obtenu') ?? 0;
            $alert = $averageScore < 10;
            return [
                'id' => $student->id,
                'name' => $student->prenom . ' ' . $student->nom,
                'avatarUrl' => null,
                'averageScore' => round($averageScore, 2),
                'alert' => $alert,
            ];
        })->toArray();
    }

    public function getStudentPerformance(int $studentId): array
    {
        $student = User::where('type_profil', 'etudiant')->findOrFail($studentId);
        $tentatives = $student->tentatives()->whereNotNull('score_obtenu')->orderBy('date_fin', 'desc')->get();
        $lastQcmScore = $tentatives->first()?->score_obtenu;
        $totalAttempts = $tentatives->count();
        $averageScore = $tentatives->avg('score_obtenu') ?? 0;
        $participation = $totalAttempts > 0 ? 'Active' : 'Faible';
        $alert = $averageScore < 10;
        
        return [
            'studentId' => $student->id,
            'name' => $student->prenom . ' ' . $student->nom,
            'lastQcmScore' => $lastQcmScore,
            'participation' => $participation,
            'averageScore' => round($averageScore, 2),
            'alert' => $alert,
        ];
    }

    public function getStudentHistory(int $studentId): array
    {
        $student = User::where('type_profil', 'etudiant')->findOrFail($studentId);
        $tentatives = $student->tentatives()
            ->whereNotNull('score_obtenu')
            ->with('qcm')
            ->orderBy('date_fin', 'desc')
            ->get();
            
        return $tentatives->map(function ($tentative) {
            return [
                'id' => $tentative->id,
                'title' => $tentative->qcm->titre,
                'date' => $tentative->date_fin?->format('Y-m-d H:i'),
                'score' => $tentative->score_obtenu,
                'totalQuestions' => $tentative->qcm->questions->count(),
            ];
        })->toArray();
    }

    public function getQcmResults(User $formateur, int $qcmId): array
    {
        $qcm = QCM::where('formateur_id', $formateur->id)->findOrFail($qcmId);
        $tentatives = Tentative::where('qcm_id', $qcmId)
            ->whereNotNull('score_obtenu')
            ->with('etudiant')
            ->orderBy('score_obtenu', 'desc')
            ->get();

        $totalQuestions = $qcm->questions()->count();
        $maxScore = $qcm->questions()->sum('points') ?: ($totalQuestions * 2);

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

        return [
            'qcmId' => $qcm->id,
            'title' => $qcm->titre,
            'results' => $formatted->toArray()
        ];
    }

    public function getResultsDashboard(User $formateur): array
    {
        $qcms = QCM::where('formateur_id', $formateur->id)
            ->with(['tentatives' => function ($query) {
                $query->with('etudiant.classe')->orderBy('date_fin', 'desc');
            }])
            ->get();

        $classes = Classe::where('formateur_id', $formateur->id)->get();
        $qcmIds = $qcms->pluck('id');

        $formattedClasses = $classes->map(function ($classe) use ($qcmIds) {
            $studentIds = User::where('classe_id', $classe->id)
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
                'tentatives' => $tentatives->toArray(),
            ];
        });

        return [
            'qcms' => $formattedQcms->toArray(),
            'classes' => $formattedClasses->toArray(),
        ];
    }

    public function updateProfile(User $formateur, array $data): array
    {
        $formateur->nom = $data['nom'];
        $formateur->prenom = $data['prenom'];
        $formateur->save();

        return [
            'id' => $formateur->id,
            'nom' => $formateur->nom,
            'prenom' => $formateur->prenom,
            'email' => $formateur->email,
            'avatarUrl' => null,
            'role' => 'Formateur Référent',
        ];
    }

    public function updatePassword(User $formateur, array $data): void
    {
        if (!Hash::check($data['current_password'], $formateur->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Le mot de passe actuel est incorrect.']
            ]);
        }

        $formateur->password = $data['password'];
        $formateur->save();
    }

    public function getPedagogieData(User $formateur, ?int $creatorFilter = null): array
    {
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

        return [
            'seances' => $formattedSeances->toArray(),
            'creators' => $formattedCreators->toArray(),
        ];
    }
}
