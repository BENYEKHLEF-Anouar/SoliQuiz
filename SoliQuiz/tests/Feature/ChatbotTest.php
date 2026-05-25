<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    /**
     * Test de la réponse réussie avec mock de l'API Gemini.
     */
    public function test_it_can_get_ai_response_with_mocked_gemini(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Bonjour ! Je suis SoliBot, votre concierge IA.']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson('/api/chatbot/chat', [
            'message' => 'Bonjour',
            'history' => []
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'reply' => 'Bonjour ! Je suis SoliBot, votre concierge IA.'
        ]);
    }

    /**
     * Test de la validation du champ message.
     */
    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/chatbot/chat', [
            'history' => []
        ]);

        $response->assertStatus(422);
    }
}
