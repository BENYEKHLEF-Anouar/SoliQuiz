<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Services\QcmService;
use App\Services\PedagogieService;
use Illuminate\View\View;

class FormateurController extends Controller
{
    protected DashboardService $dashboardService;
    protected QcmService $qcmService;
    protected PedagogieService $pedagogieService;

    public function __construct(
        DashboardService $dashboardService,
        QcmService $qcmService,
        PedagogieService $pedagogieService
    ) {
        $this->dashboardService = $dashboardService;
        $this->qcmService = $qcmService;
        $this->pedagogieService = $pedagogieService;
    }

    /**
     * Affiche le tableau de bord du formateur (SPA Shell)
     */
    public function index(): View
    {
        $user = auth()->user();
        
        $kpis = $this->dashboardService->getFormateurKpis($user->id);
        $qcms = $this->qcmService->paginate(15, null, $user->id);
        $structure = $this->pedagogieService->getGlobalStructure(); // Used for selecting UA in QCM creation

        return view('formateur.index', compact('kpis', 'qcms', 'structure'));
    }

    /**
     * Retourne les détails d'un QCM spécifique pour l'éditeur
     */
    public function qcmDetails(int $id)
    {
        $qcm = $this->qcmService->findWithRelations($id);
        
        // Security check: must be the owner
        if ($qcm->formateur_id !== auth()->id()) {
            abort(403);
        }

        return response()->json($qcm);
    }

    /**
     * Enregistre un nouveau QCM
     */
    public function storeQcm(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'duree_minutes' => 'required|integer|min:1',
            'score_reussite' => 'required|integer|min:0|max:20',
            'unite_apprentissage_id' => 'nullable|exists:unites_apprentissage,id',
        ]);

        $data['formateur_id'] = auth()->id();
        $qcm = $this->qcmService->create($data);

        return response()->json(['message' => 'QCM créé avec succès', 'qcm' => $qcm]);
    }

    /**
     * Met à jour un QCM et ses questions
     */
    public function updateQcm(Request $request, \App\Models\QCM $qcm)
    {
        if ($qcm->formateur_id !== auth()->id()) abort(403);

        $qcm = $this->qcmService->update($qcm, $request->all());

        return response()->json(['message' => 'QCM mis à jour', 'qcm' => $qcm]);
    }

    /**
     * Retourne les résultats détaillés des étudiants pour ses QCM
     */
    public function results()
    {
        // To be implemented: List of attempts for my QCMs
        return response()->json([]);
    }
}
