<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Classe;
use App\Models\User;
use App\Services\ClasseService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClasseServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected ClasseService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ClasseService();
    }

    public function test_it_can_get_all_classes()
    {
        $result = $this->service->paginate();
        $this->assertGreaterThan(0, $result->total());
    }

    public function test_it_can_filter_classes_by_search()
    {
        $formateur = User::where('type_profil', 'formateur')->first();
        Classe::create([
            'nom' => 'DWB101',
            'promotion' => '2026',
            'formateur_id' => $formateur->id,
        ]);

        $result = $this->service->paginate(15, 'DWB101');

        $this->assertGreaterThanOrEqual(1, $result->total());

        $firstClasse = collect($result->items())->first();
        $this->assertStringContainsString('DWB101', $firstClasse->nom);
    }

    public function test_it_can_create_a_classe()
    {
        $formateur = User::where('type_profil', 'formateur')->first();

        $data = [
            'nom' => 'New Classe Test',
            'promotion' => '2026',
            'formateur_id' => $formateur->id,
        ];

        $classe = $this->service->create($data);

        $this->assertDatabaseHas('classes', [
            'id' => $classe->id,
            'nom' => 'New Classe Test',
        ]);
    }

    public function test_it_can_update_a_classe()
    {
        $classe = Classe::first();

        $updatedData = [
            'nom' => 'Updated Classe Test',
            'promotion' => '2027',
        ];

        $this->service->update($classe, $updatedData);

        $this->assertDatabaseHas('classes', [
            'id' => $classe->id,
            'nom' => 'Updated Classe Test',
            'promotion' => '2027',
        ]);
    }

    public function test_it_can_delete_a_classe()
    {
        // Créer une nouvelle classe pour éviter de casser les clés étrangères d'autres tests
        $formateur = User::where('type_profil', 'formateur')->first();
        $classe = $this->service->create([
            'nom' => 'ToDelete Classe',
            'promotion' => '2026',
            'formateur_id' => $formateur->id
        ]);

        $this->service->delete($classe);

        $this->assertDatabaseMissing('classes', [
            'id' => $classe->id,
        ]);
    }
}
