<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tentative;
use App\Models\QCM;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EtudiantService
{
    /**
     * Historique paginé des tentatives terminées d'un étudiant.
     */
    public function historique(User $etudiant, int $perPage = 10): LengthAwarePaginator
    {
        return Tentative::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['reussi', 'echoue', 'abandonne'])
            ->with(['qcm.uniteApprentissage'])
            ->latest('date_fin')
            ->paginate($perPage);
    }

    /**
     * Dashboard personnel : KPIs + progression par UA.
     */
    public function getDashboard(User $etudiant): array
    {
        $tentatives = Tentative::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['reussi', 'echoue'])
            ->with('qcm')
            ->get();

        $total = $tentatives->count();
        $reussis = $tentatives->where('statut', 'reussi')->count();
        $enCoursCount = Tentative::where('etudiant_id', $etudiant->id)
            ->where('statut', 'en_cours')
            ->count();

        return [
            'nb_tentatives' => $total,
            'nb_reussies' => $reussis,
            'nb_en_cours' => $enCoursCount,
            'taux_reussite' => $total > 0 ? round(($reussis / $total) * 100, 1) : 0,
            'score_moyen' => round($tentatives->avg('score_obtenu') ?? 0, 1),
            'meilleur_score' => $tentatives->max('score_obtenu') ?? 0,
            'derniere_activite' => $tentatives->sortByDesc('date_fin')->first()?->date_fin,
        ];
    }

    /**
     * Récupère les QCM en cours pour l'étudiant.
     */
    public function getActiveSessions(User $etudiant): Collection
    {
        return Tentative::where('etudiant_id', $etudiant->id)
            ->where('statut', 'en_cours')
            ->with(['qcm.uniteApprentissage'])
            ->latest()
            ->get();
    }

    /**
     * Récupère les derniers scores (pour le graphique).
     */
    public function getLastScores(User $etudiant, int $limit = 7): array
    {
        return Tentative::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['reussi', 'echoue'])
            ->latest('date_fin')
            ->limit($limit)
            ->pluck('score_obtenu')
            ->reverse()
            ->toArray();
    }

    /**
     * Détail complet d'une tentative pour la page de résultat/feedback.
     */
    public function getTentativeDetail(User $etudiant, int $tentativeId): Tentative
    {
        return Tentative::where('etudiant_id', $etudiant->id)
            ->with([
                'qcm.questions.options',
                'reponses.choixReponses',
            ])
            ->findOrFail($tentativeId);
    }
    /**
     * Récupère les QCM à venir pour l'étudiant (ceux de sa classe non encore tentés).
     */
    public function getUpcomingQcms(User $etudiant, int $limit = 3): Collection
    {
        if (!$etudiant->classe_id) {
            return collect();
        }

        // QCM de sa classe, statut public, non encore tentés par cet étudiant
        return QCM::where('classe_id', $etudiant->classe_id)
            ->where('statut', 'public')
            ->whereDoesntHave('tentatives', function($q) use ($etudiant) {
                $q->where('etudiant_id', $etudiant->id);
            })
            ->with('uniteApprentissage')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Récupère la progression de l'étudiant par unité d'apprentissage (objectif).
     */
    public function getProgressByUa(User $etudiant): Collection
    {
        return Tentative::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['reussi', 'echoue'])
            ->with(['qcm.uniteApprentissage.seance'])
            ->get()
            ->groupBy(function ($tentative) {
                return $tentative->qcm->unite_apprentissage_id ?? 0;
            })
            ->map(function ($group) {
                $first = $group->first();
                $ua = $first->qcm->uniteApprentissage;
                $uaNom = $ua?->nom ?? 'Indépendant';
                $sessionNom = $ua?->seance?->nom;

                $avg = $group->avg('score_obtenu');
                $total = $group->count();
                $reussis = $group->where('statut', 'reussi')->count();
                return [
                    'ua_id' => $ua?->id ?? 0,
                    'ua_nom' => $uaNom,
                    'session_nom' => $sessionNom,
                    'score_moyen' => round($avg, 1),
                    'total_tentatives' => $total,
                    'taux_reussite' => $total > 0 ? round(($reussis / $total) * 100, 1) : 0,
                ];
            });
    }

    /**
     * Centralise les données de la bibliothèque pour un étudiant.
     */
    public function getBibliothequeData(User $student, ?string $search = null): array
    {
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
            ->withCount(['tentatives as mes_tentatives_count' => fn($q) => $q->where('etudiant_id', $student->id)])
            ->orderByDesc('created_at')
            ->get();

        if ($search) {
            $qcms = $qcms->filter(function($q) use ($search) {
                return str_contains(strtolower($q->titre), strtolower($search));
            });
        }
            
        $tentatives = Tentative::where('etudiant_id', $student->id)->get();
        
        $qcmList = $qcms->map(function ($qcm) use ($tentatives) {
            $tentative = $tentatives->where('qcm_id', $qcm->id)->first();
            $qcm->etat = $tentative ? $tentative->statut : 'a_faire';
            $qcm->score = $tentative ? $tentative->score_obtenu : null;
            $qcm->tentative_id = $tentative ? $tentative->id : null;
            $qcm->date_fin = $tentative ? ($tentative->date_fin ? $tentative->date_fin->format('d M Y') : null) : null;
            $qcm->unite_nom = $qcm->uniteApprentissage ? $qcm->uniteApprentissage->nom : 'Évaluation transverse';
            $qcm->url_passation = route('etudiant.passation', $qcm->id);
            $qcm->url_resultats = $qcm->tentative_id ? route('etudiant.resultats', $qcm->id) : '#';
            if ($tentative && $tentative->date_debut && $qcm->duree_minutes > 0) {
                $qcm->timer_expires_at = $tentative->date_debut->addMinutes($qcm->duree_minutes)->toIso8601String();
            } else {
                $qcm->timer_expires_at = null;
            }
            return $qcm;
        });

        $termines = $qcmList->whereIn('etat', ['reussi', 'echoue'])->sortByDesc('date_fin');
        $enCours = $qcmList->where('etat', 'en_cours');
        $aFaire = $qcmList->where('etat', 'a_faire');

        $metrics = $this->getDashboard($student);
        $moyenne = $metrics['score_moyen'] ?? 0;

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
        })->get();

        return compact('termines', 'enCours', 'aFaire', 'moyenne', 'unites');
    }

    /**
     * Recherche et filtrage de la bibliothèque pour un étudiant.
     */
    public function searchBibliotheque(User $student, ?string $search = null, ?string $statut = null, ?int $uaId = null): array
    {
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
            ->withCount(['tentatives as mes_tentatives_count' => fn($q) => $q->where('etudiant_id', $student->id)])
            ->orderByDesc('created_at')
            ->get();

        if ($search) {
            $qcms = $qcms->filter(fn($q) => str_contains(strtolower($q->titre), strtolower($search)));
        }

        if ($uaId) {
            $qcms = $qcms->filter(fn($q) => $q->unite_apprentissage_id == $uaId);
        }
            
        $tentatives = Tentative::where('etudiant_id', $student->id)->get();
        
        $qcmList = $qcms->map(function ($qcm) use ($tentatives) {
            $tentative = $tentatives->where('qcm_id', $qcm->id)->first();
            $qcm->etat = $tentative ? $tentative->statut : 'a_faire';
            $qcm->score = $tentative ? $tentative->score_obtenu : null;
            $qcm->tentative_id = $tentative ? $tentative->id : null;
            $qcm->date_fin = $tentative ? ($tentative->date_fin ? $tentative->date_fin->format('d M Y') : null) : null;
            $qcm->unite_nom = $qcm->uniteApprentissage ? $qcm->uniteApprentissage->nom : 'Évaluation transverse';
            $qcm->url_passation = route('etudiant.passation', $qcm->id);
            $qcm->url_resultats = $qcm->tentative_id ? route('etudiant.resultats', $qcm->id) : '#';
            if ($tentative && $tentative->date_debut && $qcm->duree_minutes > 0) {
                $qcm->timer_expires_at = $tentative->date_debut->addMinutes($qcm->duree_minutes)->toIso8601String();
            } else {
                $qcm->timer_expires_at = null;
            }
            return $qcm;
        });

        if ($statut) {
            $qcmList = $qcmList->filter(fn($q) => $q->etat === $statut);
        }

        return [
            'enCours' => $qcmList->where('etat', 'en_cours')->values(),
            'aFaire' => $qcmList->where('etat', 'a_faire')->values(),
            'termines' => $qcmList->whereIn('etat', ['reussi', 'echoue'])->sortByDesc('date_fin')->values(),
        ];
    }

    public function getApiScores(User $student): array
    {
        $tentatives = $student->tentatives()->whereNotNull('score_obtenu')->get();
        $globalScore = $tentatives->avg('score_obtenu') ?? 0;
        
        $cohortId = $student->classe_id;
        $ranking = 0;
        if ($cohortId) {
            $cohortStudents = User::where('classe_id', $cohortId)->where('type_profil', 'etudiant')->get();
            $scores = $cohortStudents->map(function ($s) {
                $tents = $s->tentatives()->whereNotNull('score_obtenu')->get();
                return $tents->avg('score_obtenu') ?? 0;
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

        return [
            'globalScore' => round($globalScore, 2),
            'ranking' => $ranking,
            'totalStudents' => $cohortId ? User::where('classe_id', $cohortId)->where('type_profil', 'etudiant')->count() : 0,
            'lastScores' => $lastScores ?: [],
        ];
    }

    public function getApiEvaluations(User $student): array
    {
        $attemptedQcmIds = $student->tentatives()->pluck('qcm_id');
        $pendingQcms = QCM::where('statut', 'public')
            ->whereNotIn('id', $attemptedQcmIds)
            ->get();
            
        return $pendingQcms->map(function ($qcm) {
            return [
                'id' => $qcm->id,
                'title' => $qcm->titre,
                'subject' => optional($qcm->uniteApprentissage)->nom ?? 'General',
                'dueDate' => null,
                'urgent' => false,
            ];
        })->toArray();
    }

    public function getApiHistory(User $student): array
    {
        $tentatives = $student->tentatives()
            ->whereNotNull('score_obtenu')
            ->with('qcm')
            ->orderBy('date_fin', 'desc')
            ->get();
            
        return $tentatives->map(function ($tentative) {
            return [
                'id' => $tentative->id,
                'qcm_id' => $tentative->qcm->id,
                'title' => $tentative->qcm->titre,
                'date' => $tentative->date_fin?->format('Y-m-d H:i'),
                'score' => $tentative->score_obtenu,
                'totalQuestions' => $tentative->qcm->questions->count(),
            ];
        })->toArray();
    }

    public function getApiBibliotheque(User $student): array
    {
        $student->load('classe.formateur');
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

        return [
            'qcms' => $qcmList->toArray(),
            'unites' => $unites->toArray(),
            'moyenne' => $moyenne,
            'formateur' => $formateur ? $formateur->nom_complet : null,
        ];
    }

    /**
     * Récupère le top 3 des scores de la classe/cohorte de l'étudiant pour les QCMs.
     */
    public function getCohortPodiums(User $student): Collection
    {
        if (!$student->classe_id) {
            return collect();
        }

        // QCMs de la classe
        $qcms = QCM::where('classe_id', $student->classe_id)
            ->where('statut', 'public')
            ->latest()
            ->limit(5)
            ->get();

        return $qcms->map(function ($qcm) use ($student) {
            $allTentatives = Tentative::where('qcm_id', $qcm->id)
                ->whereIn('etudiant_id', function ($query) use ($student) {
                    $query->select('id')->from('users')->where('classe_id', $student->classe_id);
                })
                ->whereIn('statut', ['reussi', 'echoue'])
                ->with('etudiant')
                ->orderByDesc('score_obtenu')
                ->orderBy('date_fin')
                ->get();

            $myAttempt = $allTentatives->where('etudiant_id', $student->id)->first();
            $myPosition = null;
            $myScore = null;
            if ($myAttempt) {
                $rank = 1;
                foreach ($allTentatives as $t) {
                    if ($t->score_obtenu > $myAttempt->score_obtenu) {
                        $rank++;
                    }
                }
                $myPosition = $rank;
                $myScore = $myAttempt->score_obtenu;
            }

            $topTentatives = $allTentatives->take(3)->values()->map(function ($t, $index) {
                return [
                    'position' => $index + 1,
                    'etudiant_nom' => $t->etudiant->prenom . ' ' . substr($t->etudiant->nom, 0, 1) . '.',
                    'score' => $t->score_obtenu,
                ];
            });

            return [
                'qcm_id' => $qcm->id,
                'qcm_titre' => $qcm->titre,
                'my_position' => $myPosition,
                'my_score' => $myScore,
                'total_students' => \App\Models\User::where('classe_id', $student->classe_id)->where('type_profil', 'etudiant')->count(),
                'podium' => $topTentatives,
            ];
        })->filter(fn($item) => $item['podium']->isNotEmpty());
    }
}
