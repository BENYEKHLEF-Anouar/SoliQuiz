<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiExplainController extends Controller
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function explainQuestion(Request $request)
    {
        $request->validate([
            'question_id'  => 'required|integer|exists:questions,id',
            'tentative_id' => 'required|integer|exists:tentatives,id',
        ]);

        try {
            $explanation = $this->aiService->explainQuestion(
                $request->question_id,
                $request->tentative_id,
                auth()->id(),
                auth()->user()->prenom
            );

            return response()->json(['explanation' => $explanation]);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
