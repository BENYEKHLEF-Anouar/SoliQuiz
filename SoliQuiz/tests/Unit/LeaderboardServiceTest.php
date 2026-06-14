<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Classe;
use App\Models\QCM;
use App\Models\Tentative;
use App\Services\LeaderboardService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LeaderboardServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected LeaderboardService $service;
    protected User $formateur;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LeaderboardService();
        $this->formateur = User::where('type_profil', 'formateur')->first() ?: User::create([
            'nom' => 'Test',
            'prenom' => 'Trainer',
            'email' => 'trainer.test.leaderboard@soliquiz.com',
            'password' => 'password',
            'type_profil' => 'formateur'
        ]);
    }

    public function test_it_can_get_overall_rankings()
    {
        $classe = Classe::create([
            'nom' => 'Test Leaderboard Classe',
            'promotion' => '2026'
        ]);

        $student1 = User::create([
            'nom' => 'Leader',
            'prenom' => 'One',
            'email' => 'student.lead1@soliquiz.com',
            'password' => 'password',
            'type_profil' => 'etudiant',
            'classe_id' => $classe->id
        ]);

        $student2 = User::create([
            'nom' => 'Follower',
            'prenom' => 'Two',
            'email' => 'student.lead2@soliquiz.com',
            'password' => 'password',
            'type_profil' => 'etudiant',
            'classe_id' => $classe->id
        ]);

        $qcm = QCM::create([
            'titre' => 'Ranking QCM',
            'duree_minutes' => 30,
            'score_reussite' => 10,
            'statut' => 'public',
            'classe_id' => $classe->id,
            'formateur_id' => $this->formateur->id
        ]);

        // Student 1 has high score (20), Student 2 has lower score (10)
        Tentative::create([
            'etudiant_id' => $student1->id,
            'qcm_id' => $qcm->id,
            'score_obtenu' => 20.0,
            'statut' => 'reussi',
            'date_debut' => now(),
            'date_fin' => now()
        ]);

        Tentative::create([
            'etudiant_id' => $student2->id,
            'qcm_id' => $qcm->id,
            'score_obtenu' => 10.0,
            'statut' => 'reussi',
            'date_debut' => now(),
            'date_fin' => now()
        ]);

        $ranking = $this->service->getOverallRanking($classe->id);

        $this->assertCount(2, $ranking);
        $this->assertEquals(1, $ranking->first()->rank);
        $this->assertEquals($student1->id, $ranking->first()->id);
        $this->assertEquals(20.0, $ranking->first()->average_score);

        $this->assertEquals(2, $ranking->last()->rank);
        $this->assertEquals($student2->id, $ranking->last()->id);
        $this->assertEquals(10.0, $ranking->last()->average_score);
    }

    public function test_it_can_get_qcm_rankings()
    {
        $classe = Classe::create([
            'nom' => 'Test Leaderboard Classe 2',
            'promotion' => '2026'
        ]);

        $student1 = User::create([
            'nom' => 'Leader',
            'prenom' => 'One',
            'email' => 'student.lead3@soliquiz.com',
            'password' => 'password',
            'type_profil' => 'etudiant',
            'classe_id' => $classe->id
        ]);

        $student2 = User::create([
            'nom' => 'Follower',
            'prenom' => 'Two',
            'email' => 'student.lead4@soliquiz.com',
            'password' => 'password',
            'type_profil' => 'etudiant',
            'classe_id' => $classe->id
        ]);

        $qcm = QCM::create([
            'titre' => 'Ranking QCM 2',
            'duree_minutes' => 30,
            'score_reussite' => 10,
            'statut' => 'public',
            'classe_id' => $classe->id,
            'formateur_id' => $this->formateur->id
        ]);

        Tentative::create([
            'etudiant_id' => $student1->id,
            'qcm_id' => $qcm->id,
            'score_obtenu' => 18.0,
            'statut' => 'reussi',
            'date_debut' => now(),
            'date_fin' => now()
        ]);

        Tentative::create([
            'etudiant_id' => $student2->id,
            'qcm_id' => $qcm->id,
            'score_obtenu' => 12.0,
            'statut' => 'reussi',
            'date_debut' => now(),
            'date_fin' => now()
        ]);

        $ranking = $this->service->getQcmRanking($qcm->id);

        $this->assertCount(2, $ranking);
        $this->assertEquals(1, $ranking->first()->rank);
        $this->assertEquals($student1->id, $ranking->first()->etudiant_id);
        $this->assertEquals(18.0, $ranking->first()->score_obtenu);

        $this->assertEquals(2, $ranking->last()->rank);
        $this->assertEquals($student2->id, $ranking->last()->etudiant_id);
        $this->assertEquals(12.0, $ranking->last()->score_obtenu);
    }
}
