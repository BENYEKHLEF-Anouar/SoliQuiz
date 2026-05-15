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
use Barryvdh\DomPDF\Facade\Pdf;
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

        return $query->where('formateur_id', $user->id);
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
        $seances = $formateur->seances()->with('unitesApprentissage.competences')->orderBy('date_debut', 'desc')->get();
        return view('formateur.pedagogie', compact('seances'));
    }

    public function storeSeance(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ]);

        Auth::user()->seances()->create($request->only('nom', 'date_debut', 'date_fin'));
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

    public function storeUA(Request $request, $seanceId)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|unique:unites_apprentissage,code',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ]);

        $seance = Auth::user()->isAdmin()
            ? Seance::findOrFail($seanceId)
            : Auth::user()->seances()->findOrFail($seanceId);
        $seance->unitesApprentissage()->create([
            'nom' => $request->nom,
            'code' => $request->code,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'user_id' => $seance->user_id,
            'seance_id' => $seance->id
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

    public function storeCompetence(Request $request, $uaId)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'code' => 'required|string|unique:competences,code',
        ]);

        $ua = Auth::user()->isAdmin()
            ? UniteApprentissage::findOrFail($uaId)
            : UniteApprentissage::where('id', $uaId)->where('user_id', Auth::id())->firstOrFail();
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
        $request->validate([
            'nom' => 'required|string|max:255', 
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ]);
        $seance = Auth::user()->isAdmin()
            ? Seance::findOrFail($id)
            : Auth::user()->seances()->findOrFail($id);
        $seance->update($request->only('nom', 'date_debut', 'date_fin'));
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
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ]);

        $ua = Auth::user()->isAdmin()
            ? UniteApprentissage::findOrFail($id)
            : UniteApprentissage::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $ua->update($request->only('nom', 'code', 'date_debut', 'date_fin'));
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
            
        $unites = UniteApprentissage::where('user_id', Auth::id())->get();
            
        return view('formateur.bibliotheque', compact('qcms', 'search', 'unites'));
    }

    public function searchBibliotheque(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $uaId = $request->input('ua_id');

        $qcms = QCM::with(['formateur', 'uniteApprentissage', 'classe.etudiants'])
            ->withCount(['questions', 'tentatives'])
            ->when($search, fn($q) => $q->where('titre', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('statut', $status))
            ->when($uaId, fn($q) => $q->where('unite_apprentissage_id', $uaId))
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
            'questions.*.points' => 'required|numeric|min:0',
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
     * Duplique un QCM existant
     */
    public function duplicateQcm($id)
    {
        $qcm = QCM::tap(fn($q) => $this->scopeQcmQueryForCurrentUser($q))
            ->findOrFail($id);

        $newQcm = $this->qcmService->duplicate($qcm);

        return back()->with('success', "Le QCM a été dupliqué avec succès sous le nom '{$newQcm->titre}'.");
    }

    /**
     * Affiche les résultats des étudiants pour une cohorte
     */
    public function resultatsCohorte()
    {
        $data = $this->qcmService->getResultsForFormateur(Auth::id());
        $classes = $data['classes'];
        $qcms = $data['qcms'];
        
        // Liste des étudiants issus de ses classes gérées
        $etudiantsClasses = $classes->flatMap->etudiants
            ->map(fn($e) => $e->prenom . ' ' . $e->nom);
            
        // Liste des étudiants ayant réellement passé ses QCM (même s'ils ne sont pas dans ses classes)
        $etudiantsTentatives = $qcms->flatMap->tentatives
            ->map(fn($t) => $t->etudiant?->prenom . ' ' . $t->etudiant?->nom)
            ->filter();

        $etudiants = $etudiantsClasses->concat($etudiantsTentatives)
            ->unique()
            ->sort()
            ->values();

        return view('formateur.resultats-cohorte', compact('classes', 'qcms', 'etudiants'));
    }

    /**
     * Exporte les résultats au format CSV ou PDF
     */
    public function exportResultats(Request $request)
    {
        $formateurId = Auth::id();
        $qcmId = $request->input('qcm');
        $classeName = $request->input('classe');
        $format = $request->input('format', 'csv');

        $query = \App\Models\Tentative::with(['etudiant.classe', 'qcm'])
            ->whereHas('qcm', function($q) use ($formateurId) {
                $q->where('formateur_id', $formateurId);
            });

        $suffix = '';
        $prefix = 'resultats_soliquiz';

        if ($classeName) {
            $query->whereHas('etudiant.classe', function($q) use ($classeName) {
                $q->where('nom', $classeName);
            });
            $prefix = 'resultats_' . \Illuminate\Support\Str::slug($classeName);
        }

        $qcmName = null;
        if ($qcmId) {
            $query->where('qcm_id', $qcmId);
            $qcm = \App\Models\QCM::find($qcmId);
            $qcmName = $qcm->titre ?? 'qcm';
            $suffix = '_' . \Illuminate\Support\Str::slug($qcmName);
        }

        $results = $query->latest()->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.resultats-pdf', [
                'results' => $results,
                'qcmName' => $qcmName,
                'classeName' => $classeName
            ]);
            return $pdf->download($prefix . $suffix . '_' . now()->format('Y-m-d_H-i') . '.pdf');
        }

        $fileName = $prefix . $suffix . '_' . now()->format('Y-m-d_H-i') . '.csv';
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($results) {
            $file = fopen('php://output', 'w');
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            
            fputcsv($file, [
                'Date',
                'Étudiant',
                'Classe',
                'QCM',
                'Durée',
                'Score',
                'Seuil Réussite',
                'Statut'
            ], ';');

            foreach ($results as $result) {
                $duree = '-';
                if ($result->date_debut && $result->date_fin) {
                    $diff = $result->date_debut->diff($result->date_fin);
                    $m = ($diff->h * 60) + $diff->i;
                    $s = $diff->s;
                    $duree = ($m > 0 ? $m . 'm ' : '') . $s . 's';
                }

                fputcsv($file, [
                    $result->date_debut?->format('d/m/Y H:i') ?? '-',
                    $result->etudiant?->nom_complet ?? 'Inconnu',
                    $result->etudiant?->classe?->nom ?? '-',
                    $result->qcm?->titre ?? '-',
                    $duree,
                    $result->score_obtenu !== null ? $result->score_obtenu . '/20' : '-',
                    ($result->qcm?->score_reussite ?? '10') . '/20',
                    ucfirst($result->statut)
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
    /**
     * Exporte un bilan individuel d'un étudiant au format PDF (pour le formateur)
     */
    public function exportTentative($id)
    {
        $tentative = \App\Models\Tentative::with(['etudiant.classe', 'qcm'])->findOrFail($id);
        
        // Vérifier l'autorisation (le formateur doit posséder le QCM)
        if (!Auth::user()->isAdmin() && $tentative->qcm->formateur_id !== Auth::id()) {
            abort(403);
        }

        $qcm = $tentative->qcm;
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
        return $pdf->download('Bilan_' . \Illuminate\Support\Str::slug($tentative->etudiant->nom_complet) . '_' . \Illuminate\Support\Str::slug($qcm->titre) . '.pdf');
    }
}
