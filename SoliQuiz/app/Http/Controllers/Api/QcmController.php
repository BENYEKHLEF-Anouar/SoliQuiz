<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tentative;
use App\Services\QcmService;
use App\Services\PassationService;
use Illuminate\Http\Request;

class QcmController extends Controller
{
    protected $qcmService;
    protected $passationService;

    public function __construct(QcmService $qcmService, PassationService $passationService)
    {
        $this->qcmService = $qcmService;
        $this->passationService = $passationService;
    }

    public function show($id)
    {
        $data = $this->qcmService->getApiDetails((int) $id);
        return response()->json($data);
    }

    public function questions($id)
    {
        $data = $this->qcmService->getApiQuestions((int) $id);
        return response()->json($data);
    }

    public function result(Request $request, $id)
    {
        try {
            $data = $this->qcmService->getApiResult($request->user(), (int) $id);
            return response()->json($data);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'No result found'], 404);
        }
    }

    public function start(Request $request, $id)
    {
        $student = $request->user();
        $state = $this->passationService->getOrCreateTentativeState($student, (int) $id);

        if ($state['status'] === 'completed') {
            return response()->json([
                'message' => $state['message'],
                'status' => 'completed'
            ], 400);
        }

        return response()->json([
            'qcmId' => $state['qcmId'],
            'title' => $state['title'],
            'durationMinutes' => $state['durationMinutes'],
            'tempsRestant' => $state['tempsRestant'],
            'initialAnswers' => $state['initialAnswers'],
        ]);
    }

    public function submit(Request $request, $id)
    {
        $tentative = Tentative::where('etudiant_id', $request->user()->id)
            ->where('qcm_id', $id)
            ->where('statut', 'en_cours')
            ->firstOrFail();

        $answers = $request->input('answers', []);
        
        $this->passationService->enregistrerReponses($tentative, $answers);
        $this->passationService->soumettre($tentative);
        
        return response()->json([
            'success' => true,
            'score' => $tentative->score_obtenu,
        ]);
    }
}