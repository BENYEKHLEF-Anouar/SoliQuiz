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
        $historique = $this->etudiantService->historique($student, 5); // top 5 recent

        return view('student.dashboard', compact('metrics', 'historique'));
    }

    /**
     * Affiche la bibliothèque de QCM de l'étudiant.
     */
    public function bibliotheque()
    {
        $student = Auth::user();
        
        // Tous les QCM publiés (avec les infos tentées via le Service Public)
        $qcms = $this->qcmPublicService->getQcmsDisponibles($student);
            
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
        $moyenne = $metrics['score_moyen'];

        return view('student.bibliotheque', compact('termines', 'enCours', 'aFaire', 'moyenne'));
    }

    /**
     * Interface de passation d'un QCM
     */
    public function passation(Request $request, $id)
    {
        $qcm = QCM::with(['uniteApprentissage', 'questions.options'])->findOrFail($id);
        
        // Utiliser le service pour démarrer la tentative
        $tentative = $this->passationService->demarrer(Auth::user(), $qcm->id);
        
        if ($tentative->statut !== 'en_cours' && !$request->has('resume')) {
            return redirect()->route('student.bibliotheque')->with('error', 'QCM déjà terminé.');
        }

        return view('student.passation', compact('qcm', 'tentative'));
    }

    /**
     * Soumet un QCM
     */
    public function submitQcm(Request $request, $id)
    {
        $qcm = QCM::with('questions.options')->findOrFail($id);
        $tentative = Tentative::where('etudiant_id', Auth::id())
            ->where('qcm_id', $qcm->id)
            ->where('statut', 'en_cours')
            ->firstOrFail();

        $answers = $request->input('answers', []);

        foreach ($qcm->questions as $question) {
            // Check if response already exists (for "resume" scenarios not fully implemented, but safe)
            $reponse = Reponse::firstOrCreate([
                'tentative_id' => $tentative->id,
                'question_id' => $question->id,
            ], [
                'repondu_a' => now()
            ]);

            // Clear old choices if resubmitting somehow
            $reponse->choixReponses()->delete();

            $selectedOptionIds = $answers[$question->id] ?? [];
            if (!is_array($selectedOptionIds)) {
                $selectedOptionIds = [$selectedOptionIds];
            }

            foreach ($selectedOptionIds as $optId) {
                ChoixReponse::create([
                    'reponse_id' => $reponse->id,
                    'option_id' => $optId
                ]);
            }
        }

        // Utiliser le service pour calculer et soumettre la tentative
        $this->passationService->soumettre($tentative);

        return redirect()->route('student.resultats', ['id' => $qcm->id]);
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
}
