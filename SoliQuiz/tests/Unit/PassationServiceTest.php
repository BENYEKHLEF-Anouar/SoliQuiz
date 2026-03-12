<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\QCM;
use App\Models\Tentative;
use App\Services\PassationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PassationServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PassationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PassationService();
    }

    public function test_it_can_demarrer_une_tentative()
    {
        $etudiant = User::where('type_profil', 'etudiant')->first();
        $qcm = QCM::first();

        $tentative = $this->service->demarrer($etudiant, $qcm->id);

        $this->assertDatabaseHas('tentatives', [
            'id' => $tentative->id,
            'statut' => 'en_cours',
            'etudiant_id' => $etudiant->id,
            'qcm_id' => $qcm->id
        ]);
    }

    public function test_it_can_soumettre_une_tentative_en_cours()
    {
        $etudiant = User::where('type_profil', 'etudiant')->first();

        // Créer un QCM léger avec une question pour tester son exécution sans fail fail key
        $qcm = QCM::create(['titre' => 'TestQcm', 'duree_minutes' => 10, 'formateur_id' => User::where('type_profil', 'formateur')->first()->id]);
        $question = $qcm->questions()->create(['texte' => 'TestQuestion1', 'type' => 'unique', 'points' => 10]);
        $question->options()->create(['texte' => 'optTrue', 'est_correcte' => true]);
        $question->options()->create(['texte' => 'optFalse', 'est_correcte' => false]);

        // Démarrer une tentative vierge
        $tentative = $this->service->demarrer($etudiant, $qcm->id);
        $this->assertEquals('en_cours', $tentative->statut);

        // Soumettre
        $resultData = $this->service->soumettre($tentative);

        $this->assertArrayHasKey('tentative', $resultData);
        $this->assertArrayHasKey('score_obtenu', $resultData);
        $this->assertArrayHasKey('reussi', $resultData);

        // Comme on n'a coché aucune option, l'étudiant doit échouer car score=0 (seuil par défaut est souvent 50 ou 20)
        $this->assertEquals('echoue', $resultData['tentative']->statut);
        $this->assertEquals(0, $resultData['score_obtenu']);

        // Vérification en base de données de la mise à jour
        $this->assertDatabaseHas('tentatives', [
            'id' => $tentative->id,
            'statut' => 'echoue',
        ]);
    }
}
