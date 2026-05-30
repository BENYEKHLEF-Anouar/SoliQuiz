<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\QCM;
use App\Models\Tentative;
use App\Services\EtudiantService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EtudiantServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected EtudiantService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EtudiantService();
    }

    public function test_it_can_get_historique()
    {
        $etudiant = User::where('type_profil', 'etudiant')->first();

        // Récupérer l'historique de l'étudiant
        $result = $this->service->historique($etudiant);

        // Assurer que le calcul de la pagination ne lève pas d'exception
        $this->assertGreaterThanOrEqual(0, $result->total());

        // Si l'historique a des éléments, vérifier la condition du statut
        foreach ($result->items() as $tentative) {
            $this->assertContains($tentative->statut, ['reussi', 'echoue', 'abandonne']);
            $this->assertEquals($etudiant->id, $tentative->etudiant_id);
        }
    }

    public function test_it_can_get_dashboard()
    {
        $etudiant = User::where('type_profil', 'etudiant')->first();

        $result = $this->service->getDashboard($etudiant);

        $this->assertArrayHasKey('nb_tentatives', $result);
        $this->assertArrayHasKey('nb_reussies', $result);
        $this->assertArrayHasKey('taux_reussite', $result);
        $this->assertArrayHasKey('score_moyen', $result);
        $this->assertArrayHasKey('meilleur_score', $result);
        $this->assertArrayHasKey('derniere_activite', $result);
    }

    public function test_it_can_get_tentative_detail()
    {
        $tentative = Tentative::first();
        if (!$tentative)
            return;

        $etudiant = User::find($tentative->etudiant_id);

        $result = $this->service->getTentativeDetail($etudiant, $tentative->id);

        $this->assertEquals($tentative->id, $result->id);
        $this->assertNotNull($result->qcm);
    }

    public function test_it_can_get_progress_by_ua()
    {
        $etudiant = User::where('type_profil', 'etudiant')->first();
        if (!$etudiant) {
            $etudiant = User::factory()->create(['type_profil' => 'etudiant']);
        }

        $result = $this->service->getProgressByUa($etudiant);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
}
