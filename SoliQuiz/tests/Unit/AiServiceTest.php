<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Qcm;
use App\Models\Question;
use App\Models\Tentative;
use App\Services\AiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AiServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected AiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AiService();
    }

    public function test_it_can_explain_question_successfully()
    {
        Http::fake([
            'http://localhost:5678/webhook/ai-explain-question' => Http::response(['explanation' => 'Cette réponse est correcte car...'], 200)
        ]);

        $student = User::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@solicode.ma',
            'password' => 'password',
            'type_profil' => 'etudiant'
        ]);

        $formateur = User::where('type_profil', 'formateur')->first() ?: User::create([
            'nom' => 'Formateur',
            'prenom' => 'Test',
            'email' => 'formateur.test@soliquiz.com',
            'password' => 'password',
            'type_profil' => 'formateur'
        ]);

        $qcm = Qcm::create([
            'titre' => 'QCM Test',
            'duree_minutes' => 30,
            'score_reussite' => 10,
            'formateur_id' => $formateur->id
        ]);

        $question = Question::create([
            'qcm_id' => $qcm->id,
            'texte' => 'Quelle est...',
            'type' => 'unique',
            'points' => 1
        ]);

        $tentative = Tentative::create([
            'etudiant_id' => $student->id,
            'qcm_id' => $qcm->id,
            'statut' => 'reussi',
            'score_obtenu' => 15,
            'date_debut' => now(),
            'date_fin' => now()
        ]);

        $explanation = $this->service->explainQuestion($question->id, $tentative->id, $student->id, $student->prenom);

        $this->assertEquals('Cette réponse est correcte car...', $explanation);
    }

    public function test_it_can_generate_qcm_successfully()
    {
        Http::fake([
            'http://localhost:5678/webhook/generate-qcm' => Http::response(['questions' => []], 200)
        ]);

        $result = $this->service->generateQcm('Laravel', 5, 'single');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('questions', $result);
    }

    public function test_it_can_chat_successfully()
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

        $result = $this->service->chat('Bonjour', [], 'session_123', '127.0.0.1', 'Mozilla');

        $this->assertEquals('Bonjour ! Je suis SoliBot, votre concierge IA.', $result['reply']);
        $this->assertEquals('session_123', $result['session_id']);
    }
}
