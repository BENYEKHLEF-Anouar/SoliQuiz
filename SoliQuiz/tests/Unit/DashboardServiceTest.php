<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DashboardServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected DashboardService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DashboardService();
    }

    public function test_it_can_get_kpis()
    {
        $result = $this->service->getKpis();

        $this->assertArrayHasKey('nb_formateurs', $result);
        $this->assertArrayHasKey('nb_etudiants', $result);
        $this->assertArrayHasKey('nb_classes', $result);
        $this->assertArrayHasKey('nb_qcms_publie', $result);
        $this->assertArrayHasKey('nb_tentatives', $result);
        $this->assertArrayHasKey('score_moyen', $result);
    }

    public function test_it_can_get_top_qcms()
    {
        $result = $this->service->getTopQcms(2);

        $this->assertLessThanOrEqual(2, $result->count());

        if ($result->count() > 0) {
            $first = $result->first();
            $this->assertNotNull($first->formateur);
            $this->assertArrayHasKey('tentatives_count', $first->toArray());
        }
    }

    public function test_it_can_get_recent_tentatives()
    {
        $result = $this->service->getRecentTentatives(3);

        $this->assertLessThanOrEqual(3, $result->count());

        if ($result->count() > 0) {
            $first = $result->first();
            $this->assertNotNull($first->etudiant);
            $this->assertNotNull($first->qcm);
        }
    }
}
