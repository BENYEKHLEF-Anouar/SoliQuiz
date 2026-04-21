<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\QCM;
use App\Services\QcmPublicService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QcmPublicServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected QcmPublicService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new QcmPublicService();
    }

    public function test_it_can_get_qcms_disponibles()
    {
        $etudiant = User::where('type_profil', 'etudiant')->first();

        $result = $this->service->getQcmsDisponibles($etudiant);

        foreach ($result as $qcm) {
            $this->assertEquals('public', $qcm->statut);
            $this->assertArrayHasKey('mes_tentatives_count', $qcm->toArray());
        }
    }

    public function test_it_can_get_qcm_pour_passation()
    {
        $qcm = QCM::where('statut', 'public')->first();

        // Récupérer le QCM masquer pour l'examen
        $result = $this->service->getQcmPourPassation($qcm->id);

        $this->assertEquals($qcm->id, $result->id);
        $this->assertNotNull($result->questions);

        // Assert that at least questions exist and don't expose answer correctness
        foreach ($result->questions as $question) {
            foreach ($question->options as $option) {
                $this->assertArrayNotHasKey('est_correcte', $option->toArray());
            }
        }
    }
}
