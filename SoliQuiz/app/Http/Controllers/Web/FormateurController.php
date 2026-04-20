<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\QCM;
use App\Models\UniteApprentissage;
use App\Services\QcmService;
use App\Services\ClasseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormateurController extends Controller
{
    private QcmService $qcmService;
    private ClasseService $classeService;

    public function __construct(QcmService $qcmService, ClasseService $classeService)
    {
        $this->qcmService = $qcmService;
        $this->classeService = $classeService;
    }

    /**
     * Tableau de bord du formateur
     */
    public function dashboard()
    {
        $formateur = Auth::user();
        
        // Load classes managed by this formateur
        $classes = $formateur->classesFormateur()->withCount('etudiants')->get();
        // Load QCM activity
        $nbQcms = QCM::where('formateur_id', $formateur->id)->count();
        $nbQcmsPublies = QCM::where('formateur_id', $formateur->id)->where('est_publie', true)->count();
        
        $metrics = [
            'nb_classes' => $classes->count(),
            'nb_etudiants' => $classes->sum('etudiants_count'),
            'nb_qcms' => $nbQcms,
            'nb_qcms_publies' => $nbQcmsPublies
        ];

        return view('formateur.dashboard', compact('metrics', 'classes'));
    }

    /**
     * Affiche la liste des QCMs créés par le formateur.
     */
    public function bibliotheque()
    {
        // On utilise la vue paginate du QcmService en y passant l'ID formateur
        $qcms = $this->qcmService->paginate(15, null, Auth::id());
        return view('formateur.bibliotheque', compact('qcms'));
    }

    /**
     * Affiche l'interface de création d'un QCM
     */
    public function createQcm()
    {
        $unites = UniteApprentissage::with('competences')->get();
        return view('formateur.creation-qcm', compact('unites'));
    }

    /**
     * Enregistre le QCM nouvellement créé.
     */
    public function storeQcm(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'unite_apprentissage_id' => 'required|exists:unites_apprentissage,id',
            'duree_minutes' => 'required|integer|min:1',
            'score_reussite' => 'required|integer|min:0|max:100',
            'est_publie' => 'nullable',
            'competence_ids' => 'nullable|array',
            'competence_ids.*' => 'exists:competences,id',
            'questions' => 'required|array|min:1',
            'questions.*.texte' => 'required|string',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.type' => 'required|in:choix_unique,choix_multiple',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.texte' => 'required|string',
            'questions.*.options.*.est_correcte' => 'nullable',
        ]);

        $data = $request->all();
        $data['formateur_id'] = Auth::id();
        $data['est_publie'] = $request->input('est_publie') === '1';
        
        // Transform the nested options syntax format logic if needed
        foreach ($data['questions'] as &$question) {
            $question['type'] = $question['type'] === 'choix_unique' ? 'unique' : 'multiple';
            foreach ($question['options'] as &$option) {
                // Ensure `est_correcte` is boolean
                $option['est_correcte'] = isset($option['est_correcte']) && $option['est_correcte'] === '1';
            }
        }

        $this->qcmService->create($data);

        return redirect()->route('formateur.bibliotheque')->with('success', 'QCM créé et assigné avec succès !');
    }

    /**
     * Supprime un QCM formateur
     */
    public function destroyQcm($id)
    {
        $qcm = QCM::where('id', $id)->where('formateur_id', Auth::id())->firstOrFail();
        $this->qcmService->delete($qcm);
        
        return redirect()->route('formateur.bibliotheque')->with('success', 'QCM effacé.');
    }

    /**
     * Affiche les résultats des étudiants pour une cohorte (MVP: liste des tentatives pour les QCM du formateur)
     */
    public function resultatsCohorte()
    {
        $data = $this->qcmService->getResultsForFormateur(Auth::id());
        $classes = $data['classes'];
        $qcms = $data['qcms'];

        return view('formateur.resultats-cohorte', compact('classes', 'qcms'));
    }
}
