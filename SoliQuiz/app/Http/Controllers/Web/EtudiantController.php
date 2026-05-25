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
use App\Services\ResultatService;

class EtudiantController extends Controller
{
    private PassationService $passationService;
    private EtudiantService $etudiantService;
    private QcmPublicService $qcmPublicService;
    private ResultatService $resultatService;

    public function __construct(
        PassationService $passationService,
        EtudiantService $etudiantService,
        QcmPublicService $qcmPublicService,
        ResultatService $resultatService
    ) {
        $this->passationService = $passationService;
        $this->etudiantService = $etudiantService;
        $this->qcmPublicService = $qcmPublicService;
        $this->resultatService = $resultatService;
    }

    /**
     * Affiche le tableau de bord de l'étudiant.
     */
    public function dashboard()
    {
        $student = Auth::user()->load('classe.formateur');
        $formateur = $student->classe ? $student->classe->formateur : null;
        $metrics = $this->etudiantService->getDashboard($student);
        $upcoming = $this->etudiantService->getUpcomingQcms($student);
        $historique = $this->etudiantService->historique($student, 5); // top 5 recent
        $lastScores = $this->etudiantService->getLastScores($student, 7);
        $activeSessions = $this->etudiantService->getActiveSessions($student);
        $progressByUa = $this->etudiantService->getProgressByUa($student);
        $cohortPodiums = $this->etudiantService->getCohortPodiums($student);

        return view('etudiant.dashboard', compact('metrics', 'historique', 'upcoming', 'lastScores', 'activeSessions', 'formateur', 'progressByUa', 'cohortPodiums'));
    }

    /**
     * Affiche la progression personnelle de l'étudiant.
     */
    public function progression()
    {
        $student = Auth::user()->load('classe.formateur');
        $formateur = $student->classe ? $student->classe->formateur : null;
        $metrics = $this->etudiantService->getDashboard($student);
        
        $tentatives = $student->tentatives()
            ->whereNotNull('score_obtenu')
            ->with(['qcm.uniteApprentissage'])
            ->orderBy('date_fin', 'asc')
            ->get();

        $totalAttempts = $tentatives->count();
        $averageScore = $totalAttempts > 0 ? round($tentatives->avg('score_obtenu'), 2) : 0;
        $successRate = $totalAttempts > 0 ? round(($tentatives->where('statut', 'reussi')->count() / $totalAttempts) * 100) : 0;

        $unites = $tentatives->map(fn($t) => $t->qcm->uniteApprentissage)
            ->filter()
            ->unique('id')
            ->values();

        $history = $tentatives->sortByDesc('date_fin');
        $progressByUa = $this->etudiantService->getProgressByUa($student);

        return view('etudiant.progression', compact('student', 'formateur', 'metrics', 'totalAttempts', 'averageScore', 'successRate', 'history', 'unites', 'progressByUa'));
    }

    /**
     * Affiche la bibliothèque de QCM de l'étudiant.
     */
    public function bibliotheque(Request $request)
    {
        $student = Auth::user()->load('classe.formateur');
        $formateur = $student->classe ? $student->classe->formateur : null;
        $search = $request->input('search');

        $data = $this->etudiantService->getBibliothequeData($student, $search);

        $termines = $data['termines'];
        $enCours = $data['enCours'];
        $aFaire = $data['aFaire'];
        $moyenne = $data['moyenne'];
        $unites = $data['unites'];

        return view('etudiant.bibliotheque', compact('termines', 'enCours', 'aFaire', 'moyenne', 'search', 'unites', 'formateur'));
    }

    /**
     * Recherche AJAX pour la bibliothèque étudiant.
     */
    public function bibliothequeSearch(Request $request)
    {
        $student = Auth::user();
        $search = $request->input('search');
        $statut = $request->input('statut');
        $uaId = $request->input('ua_id');

        $results = $this->etudiantService->searchBibliotheque(
            $student,
            $search,
            $statut,
            $uaId ? (int)$uaId : null
        );

        return response()->json($results);
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
            return redirect()->route('etudiant.bibliotheque');
        }

        // CALCUL DU TEMPS RESTANT : Heure de fin prévue - Maintenant
        // On utilise la date_debut de la tentative qui est persistée en DB
        $debut = $tentative->date_debut;
        if ($qcm->duree_minutes > 0) {
            $finPrevue = $debut->copy()->addMinutes($qcm->duree_minutes);
            $tempsRestant = (int) now()->diffInSeconds($finPrevue, false);

            // Si le temps est écoulé (négatif ou zéro), on soumet automatiquement
            if ($tempsRestant <= 0) {
                $this->passationService->soumettre($tentative);
                return redirect()->route('etudiant.resultats', $qcm->id)->with('info', 'Le temps est écoulé.');
            }
        } else {
            $tempsRestant = -1;
        }
        
        // Récupérer les réponses déjà enregistrées pour cette tentative
        $initialAnswers = [];
        $existingReponses = $tentative->reponses()->with(['choixReponses', 'question'])->get();
        foreach ($existingReponses as $reponse) {
            $options = $reponse->choixReponses->pluck('option_id')->map(fn($id) => (string)$id)->toArray();
            $initialAnswers[$reponse->question_id] = $reponse->question->type === 'unique' ? ($options[0] ?? null) : $options;
        }

        return view('etudiant.passation', compact('qcm', 'tentative', 'tempsRestant', 'initialAnswers'));
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

        return redirect()->route('etudiant.resultats', ['id' => $id])->with('success', 'QCM soumis avec succès !');
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

        $details = $this->resultatService->getTentativeDetails($tentative);
        $questionDetails = $details['questionDetails'];
        $totalQuestions = $qcm->questions->count();

        return view('etudiant.resultats', compact('qcm', 'tentative', 'questionDetails', 'totalQuestions'));
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

        $details = $this->resultatService->getTentativeDetails($tentative);
        $questionDetails = $details['questionDetails'];

        $pdf = Pdf::loadView('exports.tentative-pdf', compact('qcm', 'tentative', 'questionDetails'));
        return $pdf->download('Bilan_' . \Illuminate\Support\Str::slug($qcm->titre) . '_' . now()->format('Y-m-d') . '.pdf');
    }
}
