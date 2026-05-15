<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\QCM;
use App\Models\Tentative;
use App\Models\Reponse;
use App\Models\ChoixReponse;
use App\Services\PassationService;
use App\Services\EtudiantService;
use App\Services\QcmPublicService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    private PassationService $passationService;
    private EtudiantService $etudiantService;
    private QcmPublicService $qcmPublicService;

    public function __construct(PassationService $passationService, EtudiantService $etudiantService, QcmPublicService $qcmPublicService)
    {
        $this->passationService = $passationService;
        $this->etudiantService = $etudiantService;
        $this->qcmPublicService = $qcmPublicService;
    }

    /**
     * Affiche le tableau de bord de l'étudiant.
     */
    public function dashboard()
    {
        $student = Auth::user();
        $metrics = $this->etudiantService->getDashboard($student);
        $upcoming = $this->etudiantService->getUpcomingQcms($student);
        $historique = $this->etudiantService->historique($student, 5); // top 5 recent
        $lastScores = $this->etudiantService->getLastScores($student, 7);
        $activeSessions = $this->etudiantService->getActiveSessions($student);

        return view('student.dashboard', compact('metrics', 'historique', 'upcoming', 'lastScores', 'activeSessions'));
    }

    /**
     * Affiche la bibliothèque de QCM de l'étudiant.
     */
    public function bibliotheque(Request $request)
    {
        $student = Auth::user();
        $search = $request->input('search');
        
        // Tous les QCM publiés (avec les infos tentées via le Service Public)
        $qcms = $this->qcmPublicService->getQcmsDisponibles($student);

        // Filtrer par recherche
        if ($search) {
            $qcms = $qcms->filter(function($q) use ($search) {
                return str_contains(strtolower($q->titre), strtolower($search));
            });
        }
            
        // Tentatives de l'étudiant complètes pour l'état courant
        $tentatives = Tentative::where('etudiant_id', $student->id)->get();
        
        // Associer l'état à chaque QCM
        $qcmList = $qcms->map(function ($qcm) use ($tentatives) {
            $tentative = $tentatives->where('qcm_id', $qcm->id)->first();
            $qcm->etat = $tentative ? $tentative->statut : 'a_faire';
            $qcm->score = $tentative ? $tentative->score_obtenu : null;
            $qcm->tentative_id = $tentative ? $tentative->id : null;
            $qcm->date_fin = $tentative ? $tentative->date_fin : null;
            return $qcm;
        });

        // Filtrer
        $termines = $qcmList->whereIn('etat', ['reussi', 'echoue'])->sortByDesc('date_fin');
        $enCours = $qcmList->where('etat', 'en_cours');
        $aFaire = $qcmList->where('etat', 'a_faire');

        // Extracting average from EtudiantService logic instead to keep it DRY
        $metrics = $this->etudiantService->getDashboard($student);
        $moyenne = $metrics['score_moyen'] ?? 0;
        // Extraire les unités d'apprentissage du formateur de l'étudiant
        $unites = collect();
        if ($student->classe_id) {
            $classe = \App\Models\Classe::with('formateur')->find($student->classe_id);
            if ($classe && $classe->formateur_id) {
                $unites = \App\Models\UniteApprentissage::where('user_id', $classe->formateur_id)->get();
            }
        }

        return view('student.bibliotheque', compact('termines', 'enCours', 'aFaire', 'moyenne', 'search', 'unites'));
    }

    /**
     * Recherche AJAX pour la bibliothèque étudiant.
     */
    public function bibliothequeSearch(Request $request)
    {
        $student = Auth::user();
        $search = $request->input('search');
        $statut = $request->input('statut'); // 'reussi', 'echoue', 'a_faire', 'en_cours'
        $uaId = $request->input('ua_id');
        
        $qcms = $this->qcmPublicService->getQcmsDisponibles($student);

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
            $qcm->url_passation = route('student.passation', $qcm->id);
            $qcm->url_resultats = $qcm->tentative_id ? route('student.resultats', $qcm->id) : '#';
            return $qcm;
        });

        // Filtrer par état si demandé
        if ($statut) {
            $qcmList = $qcmList->filter(fn($q) => $q->etat === $statut);
        }

        return response()->json([
            'enCours' => $qcmList->where('etat', 'en_cours')->values(),
            'aFaire' => $qcmList->where('etat', 'a_faire')->values(),
            'termines' => $qcmList->whereIn('etat', ['reussi', 'echoue'])->sortByDesc('date_fin')->values(),
        ]);
    }

    /**
     * Interface de passation d'un QCM
     */
    public function passation(Request $request, $id)
    {
        $student = Auth::user();
        
        // Load QCM with all questions including explication feedback
        $qcm = QCM::where('statut', 'public')
            ->where(function($query) use ($student) {
                $query->whereNull('classe_id')
                      ->orWhere('classe_id', $student->classe_id);
            })
            ->with([
                'uniteApprentissage',
                'questions' => fn($q) => $q->with([
                    'options' => fn($o) => $o->select('id', 'question_id', 'texte')
                ])->select('id', 'qcm_id', 'texte', 'type', 'points')
            ])
            ->findOrFail($id);
        
        // Utiliser le service pour démarrer la tentative (reprend l'existante si elle existe)
        $tentative = $this->passationService->demarrer($student, $qcm->id);

        if ($tentative->statut !== 'en_cours') {
            return redirect()->route('student.bibliotheque')->with('error', 'QCM déjà terminé.');
        }

        // CALCUL DU TEMPS RESTANT : Heure de fin prévue - Maintenant
        // On utilise la date_debut de la tentative qui est persistée en DB
        $debut = $tentative->date_debut;
        $finPrevue = $debut->copy()->addMinutes($qcm->duree_minutes);
        $tempsRestant = (int) now()->diffInSeconds($finPrevue, false);

        // Si le temps est écoulé (négatif ou zéro), on soumet automatiquement
        if ($tempsRestant <= 0) {
            $this->passationService->soumettre($tentative);
            return redirect()->route('student.resultats', $qcm->id)->with('info', 'Le temps est écoulé.');
        }
        
        // Récupérer les réponses déjà enregistrées pour cette tentative
        $initialAnswers = [];
        $existingReponses = $tentative->reponses()->with(['choixReponses', 'question'])->get();
        foreach ($existingReponses as $reponse) {
            $options = $reponse->choixReponses->pluck('option_id')->map(fn($id) => (string)$id)->toArray();
            $initialAnswers[$reponse->question_id] = $reponse->question->type === 'unique' ? ($options[0] ?? null) : $options;
        }

        return view('student.passation', compact('qcm', 'tentative', 'tempsRestant', 'initialAnswers'));
    }

    /**
     * Sauvegarde la progression (réponses) sans soumettre le QCM.
     */
    public function saveProgress(Request $request, $id)
    {
        $tentative = Tentative::where('etudiant_id', Auth::id())
            ->where('qcm_id', $id)
            ->where('statut', 'en_cours')
            ->firstOrFail();

        $this->passationService->enregistrerReponses($tentative, $request->input('answers', []));

        return response()->json(['success' => true]);
    }

    /**
     * Soumet un QCM
     */
    public function submitQcm(Request $request, $id)
    {
        $tentative = Tentative::where('etudiant_id', Auth::id())
            ->where('qcm_id', $id)
            ->where('statut', 'en_cours')
            ->firstOrFail();

        $this->passationService->enregistrerReponses($tentative, $request->input('answers', []));
        $this->passationService->soumettre($tentative);

        return redirect()->route('student.resultats', ['id' => $id])->with('success', 'QCM soumis avec succès !');
    }

    /**
     * Affiche les résultats finaux d'un QCM
     */
    public function resultats($id)
    {
        $qcm = QCM::with('uniteApprentissage')->findOrFail($id);
        $tentative = Tentative::where('etudiant_id', Auth::id())
            ->where('qcm_id', $qcm->id)
            ->whereNotNull('score_obtenu')
            ->latest('date_fin')
            ->firstOrFail();

        $questions = $qcm->questions()->with(['options', 'reponses' => function($q) use ($tentative) {
            $q->where('tentative_id', $tentative->id);
        }])->get();

        $questionDetails = $questions->map(function ($question) {
            $userReponse = $question->reponses->first();
            $selectedOptions = $userReponse ? $userReponse->choixReponses->pluck('option_id')->toArray() : [];
            $correctOptions = $question->options->where('est_correcte', true)->pluck('id')->toArray();
            
            $isCorrect = (count($correctOptions) === count($selectedOptions)) && empty(array_diff($correctOptions, $selectedOptions));

            return (object) [
                'texte' => $question->texte,
                'points' => $question->points,
                'explication' => $question->explication_feedback,
                'isCorrect' => $isCorrect,
                'options' => $question->options->map(function($opt) use ($selectedOptions) {
                    $opt->isSelected = in_array($opt->id, $selectedOptions);
                    return $opt;
                })
            ];
        });

        $totalQuestions = $qcm->questions->count();

        return view('student.resultats', compact('qcm', 'tentative', 'questionDetails', 'totalQuestions'));
    }
    /**
     * Exporte les résultats finaux d'un QCM au format PDF
     */
    public function exportResultat($id)
    {
        $qcm = QCM::with('uniteApprentissage')->findOrFail($id);
        $tentative = Tentative::where('etudiant_id', Auth::id())
            ->where('qcm_id', $qcm->id)
            ->whereNotNull('score_obtenu')
            ->latest('date_fin')
            ->firstOrFail();

        $questions = $qcm->questions()->with(['options', 'reponses' => function($q) use ($tentative) {
            $q->where('tentative_id', $tentative->id);
        }])->get();

        $questionDetails = $questions->map(function ($question) {
            $userReponse = $question->reponses->first();
            $selectedOptions = $userReponse ? $userReponse->choixReponses->pluck('option_id')->toArray() : [];
            $correctOptions = $question->options->where('est_correcte', true)->pluck('id')->toArray();
            
            $isCorrect = (count($correctOptions) === count($selectedOptions)) && empty(array_diff($correctOptions, $selectedOptions));

            return (object) [
                'texte' => $question->texte,
                'points' => $question->points,
                'explication' => $question->explication_feedback,
                'isCorrect' => $isCorrect,
                'options' => $question->options->map(function($opt) use ($selectedOptions) {
                    $opt->isSelected = in_array($opt->id, $selectedOptions);
                    return $opt;
                })
            ];
        });

        $pdf = Pdf::loadView('exports.tentative-pdf', compact('qcm', 'tentative', 'questionDetails'));
        return $pdf->download('Bilan_' . \Illuminate\Support\Str::slug($qcm->titre) . '_' . now()->format('Y-m-d') . '.pdf');
    }
}
