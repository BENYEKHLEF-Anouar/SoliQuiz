<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\QCM;
use App\Models\UniteApprentissage;
use App\Models\Seance;
use App\Models\Competence;
use App\Models\Classe;
use App\Services\QcmService;
use App\Services\ClasseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormateurController extends Controller
{
    private QcmService $qcmService;
    private ClasseService $classeService;
    private \App\Services\DashboardService $dashboardService;

    private function requireClasseForFormateur(?int $classeId): void
    {
        if (Auth::user()->isAdmin()) {
            return;
        }

        if (!$classeId) {
            abort(422);
        }
    }

    private function assertClasseAccessibleForCurrentUser(?int $classeId): void
    {
        if (!$classeId) {
            return;
        }

        $user = Auth::user();

        if ($user->isAdmin()) {
            return;
        }

        $isOwned = Classe::where('id', $classeId)
            ->where('formateur_id', $user->id)
            ->exists();

        if (!$isOwned) {
            abort(403);
        }
    }

    private function scopeQcmQueryForCurrentUser($query)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $query;
        }

        $classeIds = $user->classeGeree()->pluck('id');

        return $query->whereIn('classe_id', $classeIds);
    }

    public function __construct(
        QcmService $qcmService,
        ClasseService $classeService,
        \App\Services\DashboardService $dashboardService
    ) {
        $this->qcmService = $qcmService;
        $this->classeService = $classeService;
        $this->dashboardService = $dashboardService;
    }

    /**
     * Tableau de bord du formateur
     */
    public function dashboard()
    {
        $formateur = Auth::user();
        $classes = $this->dashboardService->getTrainerClassesMetrics($formateur);
        $metrics = $this->dashboardService->getTrainerKpis($formateur);

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
        $seance = Auth::user()->isAdmin()
            ? Seance::findOrFail($id)
            : Auth::user()->seances()->findOrFail($id);
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

        $seance = Auth::user()->isAdmin()
            ? Seance::findOrFail($request->seance_id)
            : Auth::user()->seances()->findOrFail($request->seance_id);
        $seance->unitesApprentissage()->create([
            'nom' => $request->nom,
            'code' => $request->code,
            'user_id' => $seance->user_id
        ]);

        return back()->with('success', 'Unité d\'apprentissage ajoutée.');
    }

    public function destroyUA($id)
    {
        $ua = Auth::user()->isAdmin()
            ? UniteApprentissage::findOrFail($id)
            : UniteApprentissage::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $ua->delete();
        return back()->with('success', 'UA supprimée.');
    }

    public function storeCompetence(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'code' => 'required|string|unique:competences,code',
            'unite_apprentissage_id' => 'required|exists:unites_apprentissage,id'
        ]);

        $ua = Auth::user()->isAdmin()
            ? UniteApprentissage::findOrFail($request->unite_apprentissage_id)
            : UniteApprentissage::where('id', $request->unite_apprentissage_id)->where('user_id', Auth::id())->firstOrFail();
        $ua->competences()->create([
            'libelle' => $request->libelle,
            'code' => $request->code
        ]);

        return back()->with('success', 'Compétence ajoutée.');
    }

    public function destroyCompetence($id)
    {
        $competence = Competence::findOrFail($id);
        if (!Auth::user()->isAdmin() && $competence->uniteApprentissage->user_id !== Auth::id())
            abort(403);

        $competence->delete();
        return back()->with('success', 'Compétence supprimée.');
    }

    /**
     * Show edit form for Seance
     */
    public function editSeance($id)
    {
        $seance = Auth::user()->isAdmin()
            ? Seance::findOrFail($id)
            : Auth::user()->seances()->findOrFail($id);
        return response()->json($seance);
    }

    /**
     * Update Seance
     */
    public function updateSeance(Request $request, $id)
    {
        $request->validate(['nom' => 'required|string|max:255', 'date' => 'required|date']);
        $seance = Auth::user()->isAdmin()
            ? Seance::findOrFail($id)
            : Auth::user()->seances()->findOrFail($id);
        $seance->update($request->only('nom', 'date'));
        return back()->with('success', 'Session mise à jour.');
    }

    /**
     * Show edit form for UA
     */
    public function editUA($id)
    {
        $ua = Auth::user()->isAdmin()
            ? UniteApprentissage::findOrFail($id)
            : UniteApprentissage::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return response()->json($ua);
    }

    /**
     * Update UA
     */
    public function updateUA(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|unique:unites_apprentissage,code,' . $id,
        ]);

        $ua = Auth::user()->isAdmin()
            ? UniteApprentissage::findOrFail($id)
            : UniteApprentissage::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $ua->update($request->only('nom', 'code'));
        return back()->with('success', 'Unité d\'apprentissage mise à jour.');
    }

    /**
     * Show edit form for Competence
     */
    public function editCompetence($id)
    {
        $competence = Competence::findOrFail($id);
        if (!Auth::user()->isAdmin() && $competence->uniteApprentissage->user_id !== Auth::id())
            abort(403);
        return response()->json($competence);
    }

    /**
     * Update Competence
     */
    public function updateCompetence(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'code' => 'required|string|unique:competences,code,' . $id,
        ]);

        $competence = Competence::findOrFail($id);
        if (!Auth::user()->isAdmin() && $competence->uniteApprentissage->user_id !== Auth::id())
            abort(403);

        $competence->update([
            'libelle' => $request->libelle,
            'code' => $request->code
        ]);
        return back()->with('success', 'Compétence mise à jour.');
    }

    /**
     * Affiche la liste des QCMs créés par le formateur.
     */
    public function bibliotheque(Request $request)
    {
        $search = $request->input('search');
        $qcms = QCM::with(['formateur', 'uniteApprentissage', 'classe.etudiants'])
            ->withCount(['questions', 'tentatives'])
            ->when($search, fn($q) => $q->where('titre', 'like', "%{$search}%"))
            ->tap(fn($q) => $this->scopeQcmQueryForCurrentUser($q))
            ->latest()
            ->paginate(15);
        return view('formateur.bibliotheque', compact('qcms', 'search'));
    }

    public function searchBibliotheque(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $qcms = QCM::with(['formateur', 'uniteApprentissage', 'classe.etudiants'])
            ->withCount(['questions', 'tentatives'])
            ->when($search, fn($q) => $q->where('titre', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('statut', $status))
            ->tap(fn($q) => $this->scopeQcmQueryForCurrentUser($q))
            ->latest()
            ->paginate(50); // increased for dynamic view
        return response()->json($qcms);
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
            'classe_id' => Auth::user()->isAdmin() ? 'nullable|exists:classes,id' : 'required|exists:classes,id',
            'duree_minutes' => 'required|integer|min:1',
            'score_reussite' => 'required|numeric|min:0|max:20',
            'statut' => 'required|in:brouillon,public,termine',
            'competence_ids' => 'nullable|array',
            'competence_ids.*' => 'exists:competences,id',
            'questions' => 'required|array|min:1',
            'questions.*.texte' => 'required|string',
            'questions.*.points' => 'required|integer|min:0',
            'questions.*.type' => 'required|in:choix_unique,choix_multiple',
            'questions.*.explication_feedback' => 'nullable|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.texte' => 'required|string',
            'questions.*.options.*.est_correcte' => 'nullable',
            'questions.*.options.*.feedback_specifique' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['formateur_id'] = Auth::id();

        $classeId = isset($data['classe_id']) ? (int) $data['classe_id'] : null;
        $this->requireClasseForFormateur($classeId);
        $this->assertClasseAccessibleForCurrentUser($classeId);

        foreach ($data['questions'] as &$question) {
            $question['type'] = $question['type'] === 'choix_unique' ? 'unique' : 'multiple';
            foreach ($question['options'] as &$option) {
                $option['est_correcte'] = isset($option['est_correcte']) && $option['est_correcte'] === '1';
            }

            $nbCorrect = collect($question['options'])->where('est_correcte', true)->count();
            if ($question['type'] === 'unique' && $nbCorrect !== 1) {
                return back()->withInput()->with('error', 'Chaque question à choix unique doit avoir exactement une seule réponse correcte.');
            }
            if ($question['type'] === 'multiple' && $nbCorrect < 1) {
                return back()->withInput()->with('error', 'Chaque question à choix multiple doit avoir au moins une réponse correcte.');
            }
        }

        $this->qcmService->create($data);

        $route = Auth::user()->isAdmin() ? 'admin.qcms' : 'formateur.bibliotheque';
        return redirect()->route($route)->with('success', 'QCM créé avec succès !');
    }

    /**
     * Affiche l'interface d'édition d'un QCM
     */
    public function editQcm($id)
    {
        $qcm = QCM::with(['questions.options', 'competences', 'uniteApprentissage'])
            ->tap(fn($q) => $this->scopeQcmQueryForCurrentUser($q))
            ->findOrFail($id);

        // Admins can see all UAs/Classes, Formateurs see theirs
        if (Auth::user()->isAdmin()) {
            $unites = UniteApprentissage::with('competences')->get();
            $classes = \App\Models\Classe::all();
        } else {
            $unites = UniteApprentissage::where('user_id', Auth::id())->with('competences')->get();
            $classes = Auth::user()->classeGeree;
        }

        return view('formateur.edit-qcm', compact('qcm', 'unites', 'classes'));
    }

    /**
     * Met à jour un QCM existant
     */
    public function updateQcm(Request $request, $id)
    {
        $qcm = QCM::tap(fn($q) => $this->scopeQcmQueryForCurrentUser($q))
            ->findOrFail($id);

        // Check if QCM is already completed (termine) - prevent editing
        if ($qcm->statut === 'termine' && !Auth::user()->isAdmin()) {
            return back()->with('error', 'Ce QCM est terminé et ne peut plus être modifié.');
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'unite_apprentissage_id' => 'required|exists:unites_apprentissage,id',
            'classe_id' => 'nullable|exists:classes,id',
            'duree_minutes' => 'required|integer|min:1',
            'score_reussite' => 'required|numeric|min:0|max:20',
            'statut' => 'required|in:brouillon,public,termine',
            'competence_ids' => 'nullable|array',
            'competence_ids.*' => 'exists:competences,id',
            'questions' => 'required|array|min:1',
            'questions.*.texte' => 'required|string',
            'questions.*.points' => 'required|integer|min:0',
            'questions.*.type' => 'required|in:choix_unique,choix_multiple',
            'questions.*.explication_feedback' => 'nullable|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.texte' => 'required|string',
            'questions.*.options.*.est_correcte' => 'nullable',
            'questions.*.options.*.feedback_specifique' => 'nullable|string',
        ]);

        $data = $request->all();

        $classeId = isset($data['classe_id']) ? (int) $data['classe_id'] : null;
        $this->requireClasseForFormateur($classeId);
        $this->assertClasseAccessibleForCurrentUser($classeId);

        foreach ($data['questions'] as &$question) {
            $question['type'] = $question['type'] === 'choix_unique' ? 'unique' : 'multiple';
            foreach ($question['options'] as &$option) {
                $option['est_correcte'] = isset($option['est_correcte']) && $option['est_correcte'] === '1';
            }

            $nbCorrect = collect($question['options'])->where('est_correcte', true)->count();
            if ($question['type'] === 'unique' && $nbCorrect !== 1) {
                return back()->withInput()->with('error', 'Chaque question à choix unique doit avoir exactement une seule réponse correcte.');
            }
            if ($question['type'] === 'multiple' && $nbCorrect < 1) {
                return back()->withInput()->with('error', 'Chaque question à choix multiple doit avoir au moins une réponse correcte.');
            }
        }

        $this->qcmService->update($qcm, $data);

        $route = Auth::user()->isAdmin() ? 'admin.qcms' : 'formateur.bibliotheque';
        return redirect()->route($route)->with('success', 'QCM mis à jour avec succès !');
    }

    /**
     * Supprime un QCM formateur
     */
    public function destroyQcm($id)
    {
        $qcm = QCM::tap(fn($q) => $this->scopeQcmQueryForCurrentUser($q))
            ->findOrFail($id);
        $this->qcmService->delete($qcm);

        $route = Auth::user()->isAdmin() ? 'admin.qcms' : 'formateur.bibliotheque';
        return redirect()->route($route)->with('success', 'QCM effacé.');
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

    /**
     * Bascule le statut d'un QCM (brouillon <-> public)
     */
    public function toggleQcmStatus($id)
    {
        $qcm = QCM::tap(fn($q) => $this->scopeQcmQueryForCurrentUser($q))
            ->findOrFail($id);

        $this->qcmService->togglePublication($qcm);

        $newStatus = $qcm->fresh()->statut;
        $message = $newStatus === 'public' ? 'QCM publié et visible aux étudiants.' : 'QCM mis en brouillon.';

        return back()->with('success', $message);
    }

    /**
     * Ferme manuellement un QCM
     */
    public function closeQcm($id)
    {
        $qcm = QCM::tap(fn($q) => $this->scopeQcmQueryForCurrentUser($q))
            ->findOrFail($id);

        $this->qcmService->closeQcm($qcm);

        return back()->with('success', 'QCM fermé. Les étudiants ne peuvent plus y accéder.');
    }
}
