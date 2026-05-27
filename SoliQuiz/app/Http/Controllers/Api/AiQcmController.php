<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AiService;
use Illuminate\Http\Request;

class AiQcmController extends Controller
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generateWithAI(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'question_count' => 'required|integer|min:1|max:100',
            'question_type' => 'required|string|in:single,multiple,both',
        ]);

        try {
            $data = $this->aiService->generateQcm(
                $request->input('topic'),
                $request->input('question_count'),
                $request->input('question_type')
            );

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
