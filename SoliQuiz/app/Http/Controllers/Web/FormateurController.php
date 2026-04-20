<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\QCM;
use App\Models\UniteApprentissage;
use App\Models\Seance;
use App\Models\Competence;
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
        $classes = $formateur->classeGeree()->withCount('etudiants')->get();
        // Load QCM activity
        $nbQcms = QCM::where('formateur_id', $formateur->id)->count();
        $nbQcmsPublies = QCM::where('formateur_id', $formateur->id)->where('statut', 'public')->count();
        
        $metrics = [
            'nb_classes' => $classes->count(),
            'nb_etudiants' => $classes->sum('etudiants_count'),
            'nb_qcms' => $nbQcms,
            'nb_qcms_publies' => $nbQcmsPublies
        ];

        return view('formateur.dashboard', compact('metrics', 'classes'));
    }

    /**
     * Gestion Pédagogique (Sessions, UA, Compétences)
     */
    public function pedagogie()
    {
        $formateur = Auth::user();
        $seances = $formateur->seances()->with('unitesApprentissage.competences')->latest()->get();
        return view('formateur.pedagogie', compact('seances'));
    }

    public function storeSeance(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:255', 'date' => 'required|date']);
        Auth::user()->seances()->create($request->only('nom', 'date'));
        return back()->with('success', 'Session créée.');
    }

    public function destroySeance($id)
    {
        $seance = Auth::user()->seances()->findOrFail($id);
        $seance->delete();
        return back()->with('success', 'Session supprimée.');
    }

    public function storeUA(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|unique:unites_apprentissage,code',
            'seance_id' => 'required|exists:seances,id'
        ]);
        
        $seance = Auth::user()->seances()->findOrFail($request->seance_id);
        $seance->unitesApprentissage()->create([
            'nom' => $request->nom,
            'code' => $request->code,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', 'Unité d\'apprentissage ajoutée.');
    }

    public function destroyUA($id)
    {
        $ua = UniteApprentissage::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $ua->delete();
        return back()->with('success', 'UA supprimée.');
    }

    public function storeCompetence(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|unique:competences,code',
            'unite_apprentissage_id' => 'required|exists:unites_apprentissage,id'
        ]);
        
        $ua = UniteApprentissage::where('id', $request->unite_apprentissage_id)->where('user_id', Auth::id())->firstOrFail();
        $ua->competences()->create($request->only('nom', 'code'));

        return back()->with('success', 'Compétence ajoutée.');
    }

    public function destroyCompetence($id)
    {
        $competence = Competence::findOrFail($id);
        // Check privacy through UA
        if ($competence->uniteApprentissage->user_id !== Auth::id()) abort(403);
        
        $competence->delete();
        return back()->with('success', 'Compétence supprimée.');
    }

    /**
     * Affiche la liste des QCMs créés par le formateur.
     */
    public function bibliotheque()
    {
        $qcms = $this->qcmService->paginate(15, null, Auth::id());
        return view('formateur.bibliotheque', compact('qcms'));
    }

    /**
     * Affiche l'interface de création d'un QCM
     */
    public function createQcm()
    {
        $unites = UniteApprentissage::where('user_id', Auth::id())->with('competences')->get();
        $classes = Auth::user()->classeGeree;
        return view('formateur.creation-qcm', compact('unites', 'classes'));
    }

    /**
     * Enregistre le QCM nouvellement créé.
     */
    public function storeQcm(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'unite_apprentissage_id' => 'required|exists:unites_apprentissage,id',
            'classe_id' => 'nullable|exists:classes,id',
            'duree_minutes' => 'required|integer|min:1',
            'score_reussite' => 'required|integer|min:0|max:100',
            'statut' => 'required|in:brouillon,public,termine',
            'competence_ids' => 'nullable|array',
            'competence_ids.*' => 'exists:competences,id',
            'questions' => 'required|array|min:1',
            'questions.*.texte' => 'required|string',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.type' => 'required|in:choix_unique,choix_multiple',
            'questions.*.explication_feedback' => 'nullable|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.texte' => 'required|string',
            'questions.*.options.*.est_correcte' => 'nullable',
            'questions.*.options.*.feedback_specifique' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['formateur_id'] = Auth::id();
        
        foreach ($data['questions'] as &$question) {
            $question['type'] = $question['type'] === 'choix_unique' ? 'unique' : 'multiple';
            foreach ($question['options'] as &$option) {
                $option['est_correcte'] = isset($option['est_correcte']) && $option['est_correcte'] === '1';
            }
        }

        $this->qcmService->create($data);

        return redirect()->route('formateur.bibliotheque')->with('success', 'QCM créé avec succès !');
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
     * Affiche les résultats des étudiants pour une cohorte
     */
    public function resultatsCohorte()
    {
        $data = $this->qcmService->getResultsForFormateur(Auth::id());
        $classes = $data['classes'];
        $qcms = $data['qcms'];

        return view('formateur.resultats-cohorte', compact('classes', 'qcms'));
    }
}
