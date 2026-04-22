<?php

namespace App\Http\Controllers;

use App\Models\Seance;
use App\Models\UniteApprentissage;
use App\Models\Competence;
use App\Services\PedagogieService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PedagogieController extends Controller
{
    protected $service;

    public function __construct(PedagogieService $service)
    {
        $this->service = $service;
    }

    /**
     * Séances
     */
    public function storeSeance(Request $request): JsonResponse
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'ordre' => 'nullable|integer',
        ]);
        
        $data['user_id'] = auth()->id();
        $seance = $this->service->createSeance($data);

        return response()->json([
            'message' => 'Séance créée avec succès',
            'seance' => $seance
        ]);
    }

    public function updateSeance(Request $request, Seance $seance): JsonResponse
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'ordre' => 'nullable|integer',
        ]);

        $seance = $this->service->updateSeance($seance, $data);

        return response()->json([
            'message' => 'Séance mise à jour',
            'seance' => $seance
        ]);
    }

    public function destroySeance(Seance $seance): JsonResponse
    {
        $this->service->deleteSeance($seance);
        return response()->json(['message' => 'Séance supprimée']);
    }

    /**
     * Unités d'Apprentissage (UA)
     */
    public function storeUA(Request $request): JsonResponse
    {
        $data = $request->validate([
            'seance_id' => 'required|exists:seances,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $ua = $this->service->createUA($data);

        return response()->json([
            'message' => 'UA créée avec succès',
            'ua' => $ua
        ]);
    }

    public function updateUA(Request $request, UniteApprentissage $ua): JsonResponse
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $ua = $this->service->updateUA($ua, $data);

        return response()->json([
            'message' => 'UA mise à jour',
            'ua' => $ua
        ]);
    }

    public function destroyUA(UniteApprentissage $ua): JsonResponse
    {
        $this->service->deleteUA($ua);
        return response()->json(['message' => 'UA supprimée']);
    }

    /**
     * Compétences
     */
    public function storeCompetence(Request $request): JsonResponse
    {
        $data = $request->validate([
            'unite_apprentissage_id' => 'required|exists:unites_apprentissage,id',
            'titre' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        $competence = $this->service->createCompetence($data);

        return response()->json([
            'message' => 'Compétence créée avec succès',
            'competence' => $competence
        ]);
    }

    public function updateCompetence(Request $request, Competence $competence): JsonResponse
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        $competence = $this->service->updateCompetence($competence, $data);

        return response()->json([
            'message' => 'Compétence mise à jour',
            'competence' => $competence
        ]);
    }

    public function destroyCompetence(Competence $competence): JsonResponse
    {
        $this->service->deleteCompetence($competence);
        return response()->json(['message' => 'Compétence supprimée']);
    }
}
