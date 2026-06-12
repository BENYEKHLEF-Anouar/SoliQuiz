<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\QCM;
use App\Models\Tentative;
use App\Services\ResultatService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;

class ResultatServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected ResultatService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ResultatService();
    }

    public function test_it_can_get_tentative_details()
    {
        $etudiant = User::where('type_profil', 'etudiant')->first();
        $formateur = User::where('type_profil', 'formateur')->first();

        $qcm = QCM::create([
            'titre' => 'TestQcmResultat',
            'duree_minutes' => 10,
            'score_reussite' => 10,
            'formateur_id' => $formateur->id
        ]);
        
        $tentative = Tentative::create([
            'qcm_id' => $qcm->id,
            'etudiant_id' => $etudiant->id,
            'statut' => 'reussi',
            'score_obtenu' => 15,
        ]);

        $details = $this->service->getTentativeDetails($tentative);

        $this->assertArrayHasKey('qcm', $details);
        $this->assertArrayHasKey('tentative', $details);
        $this->assertArrayHasKey('questionDetails', $details);
    }

    public function test_it_can_get_cohorte_results()
    {
        $etudiant = User::where('type_profil', 'etudiant')->first();
        $formateur = User::where('type_profil', 'formateur')->first();

        Auth::login($formateur);

        $qcm = QCM::create([
            'titre' => 'CohorteQcmTest',
            'duree_minutes' => 10,
            'score_reussite' => 10,
            'formateur_id' => $formateur->id
        ]);
        
        $tentative = Tentative::create([
            'qcm_id' => $qcm->id,
            'etudiant_id' => $etudiant->id,
            'statut' => 'reussi',
            'score_obtenu' => 15,
        ]);

        $results = $this->service->getCohorteResults([
            'qcm_id' => $qcm->id
        ]);

        $this->assertGreaterThan(0, $results->count());
        $this->assertEquals($qcm->id, $results->first()->qcm_id);
    }
}
