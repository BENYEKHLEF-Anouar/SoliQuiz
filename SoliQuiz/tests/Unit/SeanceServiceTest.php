<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Seance;
use App\Services\SeanceService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SeanceServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected SeanceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SeanceService();
    }

    public function test_it_can_get_all_seances()
    {
        $result = $this->service->paginate();
        $this->assertGreaterThan(0, $result->total());
    }

    public function test_it_can_filter_seances_by_search()
    {
        $result = $this->service->paginate(15, 'HTML');

        $this->assertGreaterThanOrEqual(1, $result->total());

        $firstSeance = collect($result->items())->first();
        $this->assertStringContainsString('HTML', $firstSeance->nom);
    }

    public function test_it_can_create_a_seance()
    {
        $data = [
            'nom' => 'New Seance Test',
            'date' => '2026-05-01',
        ];

        $seance = $this->service->create($data);

        $this->assertDatabaseHas('seances', [
            'id' => $seance->id,
            'nom' => 'New Seance Test',
        ]);
    }

    public function test_it_can_update_a_seance()
    {
        $seance = Seance::first();

        $updatedData = [
            'nom' => 'Updated Seance Test',
            'date' => '2026-05-02',
        ];

        $this->service->update($seance, $updatedData);

        $this->assertDatabaseHas('seances', [
            'id' => $seance->id,
            'nom' => 'Updated Seance Test',
        ]);
    }

    public function test_it_can_delete_a_seance()
    {
        // Créer une nouvelle séance pour ne pas impacter les autres tests via clés étrangères
        $seance = $this->service->create([
            'nom' => 'ToDelete Seance',
            'date' => '2026-06-01',
        ]);

        $this->service->delete($seance);

        $this->assertDatabaseMissing('seances', [
            'id' => $seance->id,
        ]);
    }
}
