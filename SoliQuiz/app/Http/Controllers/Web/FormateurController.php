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
use App\Services\ResultatService;
use App\Services\SeanceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormateurController extends Controller
{
    private QcmService $qcmService;
    private ClasseService $classeService;
    private \App\Services\DashboardService $dashboardService;
    private ResultatService $resultatService;
    private SeanceService $seanceService;

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
        \App\Services\DashboardService $dashboardService,
        ResultatService $resultatService,
        SeanceService $seanceService
    ) {
        $this->qcmService = $qcmService;
        $this->classeService = $classeService;
        $this->dashboardService = $dashboardService;
        $this->resultatService = $resultatService;
        $this->seanceService = $seanceService;
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
        $seances = $this->seanceService->getSeancesWithRelations(Auth::user()->isAdmin() ? null : Auth::id());
        return view('formateur.pedagogie', compact('seances'));
    }

    public function storeSeance(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ]);

        $this->seanceService->create([
            'nom' => $request->nom,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'user_id' => Auth::id()
        ]);
        return back()->with('success', 'Session créée.');
    }

    public function destroySeance($id)
    {
        $seance = Auth::user()->isAdmin()
            ? Seance::findOrFail($id)
            : Auth::user()->seances()->findOrFail($id);
        $this->seanceService->delete($seance);
        return back()->with('success', 'Session supprimée.');
    }

    public function storeUA(Request $request, $seanceId)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ]);

        $codeExists = UniteApprentissage::where('code', $request->code)->exists();

        $seance = Auth::user()->isAdmin()
            ? Seance::findOrFail($seanceId)
            : Auth::user()->seances()->findOrFail($seanceId);

        $this->seanceService->addUniteApprentissage($seance, [
            'nom' => $request->nom,
            'code' => $request->code,
            'user_id' => $seance->user_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        $redirect = back()->with('success', 'Unité d\'apprentissage ajoutée.');
        if ($codeExists) {
            $redirect->with('code_warning', 'Attention : Le code de l\'UA est déjà utilisé.');
        }
        return $redirect;
    }

    public function destroyUA($id)
    {
        $ua = Auth::user()->isAdmin()
            ? UniteApprentissage::findOrFail($id)
            : UniteApprentissage::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $this->seanceService->deleteUniteApprentissage($ua);
        return back()->with('success', 'UA supprimée.');
    }

    public function storeCompetence(Request $request, $uaId)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'code' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $codeExists = Competence::where('code', $request->code)->exists();

        $ua = Auth::user()->isAdmin()
            ? UniteApprentissage::findOrFail($uaId)
            : UniteApprentissage::where('id', $uaId)->where('user_id', Auth::id())->firstOrFail();

        $this->seanceService->addCompetence($ua, [
            'code' => $request->code,
            'libelle' => $request->libelle,
            'description' => $request->description
        ]);

        $redirect = back()->with('success', 'Compétence ajoutée.');
        if ($codeExists) {
            $redirect->with('code_warning', 'Attention : Le code de la compétence est déjà utilisé.');
        }
        return $redirect;
    }

    public function destroyCompetence($id)
    {
        $competence = Competence::findOrFail($id);
        if (!Auth::user()->isAdmin() && $competence->uniteApprentissage->user_id !== Auth::id())
            abort(403);

        $this->seanceService->deleteCompetence($competence);
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
        $this->seanceService->update($seance, $request->only('nom', 'date_debut', 'date_fin'));
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
            'code' => 'required|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ]);

        $ua = Auth::user()->isAdmin()
            ? UniteApprentissage::findOrFail($id)
            : UniteApprentissage::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $this->seanceService->updateUniteApprentissage($ua, $request->only('nom', 'code', 'date_debut', 'date_fin'));
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
            'code' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $competence = Competence::findOrFail($id);
        if (!Auth::user()->isAdmin() && $competence->uniteApprentissage->user_id !== Auth::id())
            abort(403);

        $this->seanceService->updateCompetence($competence, [
            'code' => $request->code,
            'libelle' => $request->libelle,
            'description' => $request->description
        ]);
        return back()->with('success', 'Compétence mise à jour.');
    }

    /**
     * Affiche la liste des QCMs créés par le formateur.
     */
    public function bibliotheque(Request $request)
    {
        $search = $request->input('search');
        $qcms = $this->qcmService->paginate(
            15,
            $search,
            Auth::user()->isAdmin() ? null : Auth::id()
        );

        $unites = UniteApprentissage::where('user_id', Auth::id())->get();

        return view('formateur.bibliotheque', compact('qcms', 'search', 'unites'));
    }

    public function searchBibliotheque(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $uaId = $request->input('ua_id');

        $qcms = $this->qcmService->paginate(
            50,
            $search,
            Auth::user()->isAdmin() ? null : Auth::id(),
            $status,
            $uaId ? (int)$uaId : null
        );
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
            'duree_minutes' => 'required|integer|min:0',
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
        ], $this->getQcmValidationMessages());

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
            'duree_minutes' => 'required|integer|min:0',
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
        ], $this->getQcmValidationMessages());

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
        $qcmId = $request->input('qcm');
        $classeName = $request->input('classe');
        $format = $request->input('format', 'csv');

        $suffix = '';
        $prefix = 'resultats_soliquiz';

        if ($classeName) {
            $prefix = 'resultats_' . \Illuminate\Support\Str::slug($classeName);
        }

        $qcmName = null;
        if ($qcmId) {
            $qcm = \App\Models\QCM::find($qcmId);
            $qcmName = $qcm->titre ?? 'qcm';
            $suffix = '_' . \Illuminate\Support\Str::slug($qcmName);
        }

        $results = $this->resultatService->getCohorteResults([
            'classe' => $classeName,
            'qcm_id' => $qcmId
        ]);

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.resultats-pdf', [
                'results' => $results,
                'qcmName' => $qcmName,
                'classeName' => $classeName
            ]);
            return $pdf->download($prefix . $suffix . '_' . now()->format('Y-m-d_H-i') . '.pdf');
        }

        if ($format === 'excel' || $format === 'xls') {
            $fileName = $prefix . $suffix . '_' . now()->format('Y-m-d_H-i') . '.xls';

            $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            $html .= '<head><meta http-equiv="Content-type" content="text/html;charset=utf-8" /></head>';
            $html .= '<body>';
            $html .= '<table border="1">';
            $html .= '<tr style="background-color: #4F46E5; color: #FFFFFF; font-weight: bold;">';
            $html .= '<th>Date</th><th>Étudiant</th><th>Classe</th><th>QCM</th><th>Durée</th><th>Score</th><th>Seuil Réussite</th><th>Statut</th>';
            $html .= '</tr>';

            foreach ($results as $result) {
                $duree = '-';
                if ($result->date_debut && $result->date_fin) {
                    $diff = $result->date_debut->diff($result->date_fin);
                    $m = ($diff->h * 60) + $diff->i;
                    $s = $diff->s;
                    $duree = ($m > 0 ? $m . 'm ' : '') . $s . 's';
                }

                $html .= '<tr>';
                $html .= '<td>' . ($result->date_debut?->format('d/m/Y H:i') ?? '-') . '</td>';
                $html .= '<td>' . htmlspecialchars($result->etudiant?->nom_complet ?? 'Inconnu') . '</td>';
                $html .= '<td>' . htmlspecialchars($result->etudiant?->classe?->nom ?? '-') . '</td>';
                $html .= '<td>' . htmlspecialchars($result->qcm?->titre ?? '-') . '</td>';
                $html .= '<td>' . $duree . '</td>';
                $html .= '<td>' . ($result->score_obtenu !== null ? $result->score_obtenu . '/20' : '-') . '</td>';
                $html .= '<td>' . ($result->qcm?->score_reussite ?? '10') . '/20' . '</td>';
                $html .= '<td>' . ucfirst($result->statut) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</table></body></html>';

            return response($html, 200, [
                'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ]);
        }

        $fileName = $prefix . $suffix . '_' . now()->format('Y-m-d_H-i') . '.csv';
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($results) {
            $file = fopen('php://output', 'w');
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

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

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'statut' => $newStatus,
                'message' => $message
            ]);
        }

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
     * Exporte un bilan individuel d'un étudiant au format PDF, Excel, ou CSV (pour le formateur)
     */
    public function exportTentative(Request $request, $id)
    {
        $tentative = \App\Models\Tentative::with(['etudiant.classe', 'qcm'])->findOrFail($id);

        // Vérifier l'autorisation (le formateur doit posséder le QCM)
        if (!Auth::user()->isAdmin() && $tentative->qcm->formateur_id !== Auth::id()) {
            abort(403);
        }

        $details = $this->resultatService->getTentativeDetails($tentative);
        $qcm = $details['qcm'];
        $questionDetails = $details['questionDetails'];

        $format = $request->input('format', 'pdf');

        if ($format === 'excel' || $format === 'xls') {
            $fileName = 'Bilan_' . \Illuminate\Support\Str::slug($tentative->etudiant->nom_complet) . '_' . \Illuminate\Support\Str::slug($qcm->titre) . '.xls';

            $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            $html .= '<head><meta http-equiv="Content-type" content="text/html;charset=utf-8" /></head>';
            $html .= '<body>';

            $html .= '<h2>Bilan Individuel - SoliQuiz</h2>';
            $html .= '<table>';
            $html .= '<tr><td><b>Étudiant :</b></td><td>' . htmlspecialchars($tentative->etudiant?->nom_complet) . '</td></tr>';
            $html .= '<tr><td><b>Classe :</b></td><td>' . htmlspecialchars($tentative->etudiant?->classe?->nom ?? '-') . '</td></tr>';
            $html .= '<tr><td><b>QCM :</b></td><td>' . htmlspecialchars($qcm->titre) . '</td></tr>';
            $html .= '<tr><td><b>Score :</b></td><td><b>' . $tentative->score_obtenu . '/20</b></td></tr>';
            $html .= '<tr><td><b>Seuil Réussite :</b></td><td>' . $qcm->score_reussite . '/20</td></tr>';
            $html .= '<tr><td><b>Statut :</b></td><td>' . ucfirst($tentative->statut) . '</td></tr>';
            $html .= '</table><br/><br/>';

            $html .= '<table border="1">';
            $html .= '<tr style="background-color: #4F46E5; color: #FFFFFF; font-weight: bold;">';
            $html .= '<th>N°</th><th>Question</th><th>Points</th><th>Résultat</th><th>Explication</th>';
            $html .= '</tr>';

            foreach ($questionDetails as $idx => $qd) {
                $html .= '<tr>';
                $html .= '<td>' . ($idx + 1) . '</td>';
                $html .= '<td>' . htmlspecialchars($qd->texte) . '</td>';
                $html .= '<td>' . $qd->points . '</td>';
                $html .= '<td>' . ($qd->isCorrect ? 'Correct' : 'Incorrect') . '</td>';
                $html .= '<td>' . htmlspecialchars($qd->explication ?? '-') . '</td>';
                $html .= '</tr>';
            }

            $html .= '</table></body></html>';

            return response($html, 200, [
                'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ]);
        }

        if ($format === 'csv') {
            $fileName = 'Bilan_' . \Illuminate\Support\Str::slug($tentative->etudiant->nom_complet) . '_' . \Illuminate\Support\Str::slug($qcm->titre) . '.csv';

            $headers = [
                "Content-type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function () use ($tentative, $qcm, $questionDetails) {
                $file = fopen('php://output', 'w');
                fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

                fputcsv($file, ['Bilan Individuel - SoliQuiz'], ';');
                fputcsv($file, ['Étudiant', $tentative->etudiant?->nom_complet], ';');
                fputcsv($file, ['Classe', $tentative->etudiant?->classe?->nom ?? '-'], ';');
                fputcsv($file, ['QCM', $qcm->titre], ';');
                fputcsv($file, ['Score', $tentative->score_obtenu . '/20'], ';');
                fputcsv($file, ['Seuil Réussite', $qcm->score_reussite . '/20'], ';');
                fputcsv($file, ['Statut', ucfirst($tentative->statut)], ';');
                fputcsv($file, [], ';');

                fputcsv($file, [
                    'N°',
                    'Question',
                    'Points',
                    'Résultat',
                    'Explication'
                ], ';');

                foreach ($questionDetails as $idx => $qd) {
                    fputcsv($file, [
                        $idx + 1,
                        $qd->texte,
                        $qd->points,
                        $qd->isCorrect ? 'Correct' : 'Incorrect',
                        $qd->explication ?? '-'
                    ], ';');
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $pdf = Pdf::loadView('exports.tentative-pdf', compact('qcm', 'tentative', 'questionDetails'));
        return $pdf->download('Bilan_' . \Illuminate\Support\Str::slug($tentative->etudiant->nom_complet) . '_' . \Illuminate\Support\Str::slug($qcm->titre) . '.pdf');
    }

    private function getQcmValidationMessages(): array
    {
        return [
            'titre.required' => "Le titre du QCM est obligatoire.",
            'unite_apprentissage_id.required' => "Veuillez sélectionner une Unité d'Apprentissage (UA).",
            'unite_apprentissage_id.exists' => "L'unité d'apprentissage sélectionnée est invalide.",
            'classe_id.required' => "Veuillez sélectionner une Cohorte Cible.",
            'classe_id.exists' => "La classe sélectionnée est invalide.",
            'duree_minutes.required' => "La durée en minutes est requise.",
            'duree_minutes.integer' => "La durée doit être un nombre entier.",
            'duree_minutes.min' => "La durée ne peut pas être négative.",
            'score_reussite.required' => "Le score de réussite est requis.",
            'score_reussite.numeric' => "Le score de réussite doit être un nombre.",
            'score_reussite.min' => "Le score de réussite doit être au moins 0.",
            'score_reussite.max' => "Le score de réussite ne peut pas dépasser 20.",
            'questions.required' => "Le QCM doit contenir au moins une question.",
            'questions.array' => "Le format des questions est invalide.",
            'questions.min' => "Le QCM doit contenir au moins une question.",
            'questions.*.texte.required' => "L'énoncé de chaque question est obligatoire.",
            'questions.*.points.required' => "Le barème (points) pour chaque question est obligatoire.",
            'questions.*.points.numeric' => "Le barème d'une question doit être un nombre.",
            'questions.*.points.min' => "Le barème d'une question doit être au moins 0.",
            'questions.*.type.required' => "Le type de chaque question est obligatoire.",
            'questions.*.type.in' => "Le type de question sélectionné est invalide.",
            'questions.*.options.required' => "Chaque question doit avoir des options de réponse.",
            'questions.*.options.array' => "Les options de réponse doivent être au format correct.",
            'questions.*.options.min' => "Chaque question doit avoir au moins 2 options de réponse.",
            'questions.*.options.*.texte.required' => "Le texte de l'option de réponse est obligatoire.",
        ];
    }

    public function generateWithAI(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'question_count' => 'required|integer|min:1|max:100',
            'question_type' => 'required|string|in:single,multiple,both',
        ]);

        try {
            // 1. Try production webhook first
            $response = \Illuminate\Support\Facades\Http::timeout(120)->post('http://localhost:5678/webhook/generate-qcm', [
                'topic' => $request->input('topic'),
                'question_count' => $request->input('question_count'),
                'question_type' => $request->input('question_type'),
            ]);

            // 2. If it fails or returns 404 (because workflow is not active yet), fallback to test webhook
            if ($response->failed() || $response->status() === 404) {
                $response = \Illuminate\Support\Facades\Http::timeout(120)->post('http://localhost:5678/webhook-test/generate-qcm', [
                    'topic' => $request->input('topic'),
                    'question_count' => $request->input('question_count'),
                    'question_type' => $request->input('question_type'),
                ]);
            }

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Error generating questions from the AI.'], 500);
        } catch (\Exception $e) {
            // 3. Last resort fallback in case of connection refused/timeout on production port
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(120)->post('http://localhost:5678/webhook-test/generate-qcm', [
                    'topic' => $request->input('topic'),
                    'question_count' => $request->input('question_count'),
                    'question_type' => $request->input('question_type'),
                ]);

                if ($response->successful()) {
                    return response()->json($response->json());
                }
            } catch (\Exception $subEx) {
                // Both connections failed
            }

            return response()->json(['error' => 'Could not connect to n8n. Make sure n8n is running on port 5678.'], 500);
        }
    }
}
