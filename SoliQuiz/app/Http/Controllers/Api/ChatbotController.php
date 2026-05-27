<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message'    => 'required|string|max:2000',
            'history'    => 'nullable|array',
            'session_id' => 'nullable|string|max:64',
        ]);

        $message   = $request->input('message');
        $history   = $request->input('history', []);
        $sessionId = $request->input('session_id') ?: Str::uuid()->toString();
        $ip        = $request->ip();
        $userAgent = substr($request->userAgent() ?? '', 0, 255);

        try {
            $data = $this->aiService->chat($message, $history, $sessionId, $ip, $userAgent);

            return response()->json($data);

        } catch (\Exception $e) {
            $code = $e->getCode() === 429 ? 429 : 500;
            return response()->json(['error' => $e->getMessage(), 'session_id' => $sessionId], $code);
        }
    }
}
