<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\QCM;
use App\Models\User;
use App\Models\UniteApprentissage;
use App\Services\QcmService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QcmServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected QcmService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new QcmService();
    }

    public function test_it_can_get_all_qcms()
    {
        $result = $this->service->paginate();
        $this->assertGreaterThan(0, $result->total());
    }

    public function test_it_can_filter_qcms_by_search_title()
    {
        $result = $this->service->paginate(15, 'PHP');

        $this->assertGreaterThanOrEqual(1, $result->total());

        $firstQcm = collect($result->items())->first();
        $this->assertStringContainsString('PHP', $firstQcm->titre);
    }

    public function test_it_can_filter_qcms_by_formateur()
    {
        // Pick a formateur that exists in your seeded data
        $formateur = User::where('type_profil', 'formateur')->first();

        $result = $this->service->paginate(15, null, $formateur->id);

        $this->assertGreaterThan(0, $result->total());

        // Ensure every returned qcm belongs to the selected formateur
        foreach ($result->items() as $qcm) {
            $this->assertEquals($formateur->id, $qcm->formateur_id);
        }
    }

    public function test_it_can_create_a_qcm_with_questions()
    {
        $formateur = User::where('type_profil', 'formateur')->first();
        $ua = UniteApprentissage::first();

        $data = [
            'titre' => 'New QCM Test',
            'duree_minutes' => 30,
            'score_reussite' => 20,
            'statut' => 'public',
            'formateur_id' => $formateur->id,
            'unite_apprentissage_id' => $ua->id,
            'questions' => [
                [
                    'texte' => 'Question Test 1',
                    'type' => 'unique',
                    'points' => 1,
                    'options' => [
                        ['texte' => 'Option 1', 'est_correcte' => true],
                        ['texte' => 'Option 2', 'est_correcte' => false],
                    ],
                ]
            ],
        ];

        $qcm = $this->service->create($data);

        $this->assertDatabaseHas('qcms', [
            'id' => $qcm->id,
            'titre' => 'New QCM Test',
        ]);

        $this->assertDatabaseHas('questions', [
            'qcm_id' => $qcm->id,
            'texte' => 'Question Test 1',
        ]);
    }

    public function test_it_can_update_a_qcm()
    {
        $qcm = QCM::first();

        $updatedData = [
            'titre' => 'Updated QCM Test',
            'duree_minutes' => 45,
        ];

        $this->service->update($qcm, $updatedData);

        $this->assertDatabaseHas('qcms', [
            'id' => $qcm->id,
            'titre' => 'Updated QCM Test',
            'duree_minutes' => 45,
        ]);
    }

    public function test_it_can_delete_a_qcm()
    {
        // Créer un qcm pour le supprimer (pour préserver l'intégrité de la DB)
        $formateur = User::where('type_profil', 'formateur')->first();
        $qcm = $this->service->create([
            'titre' => 'ToDelete QCM',
            'duree_minutes' => 10,
            'formateur_id' => $formateur->id
        ]);

        $this->service->delete($qcm);

        $this->assertDatabaseMissing('qcms', [
            'id' => $qcm->id,
        ]);
    }
}
