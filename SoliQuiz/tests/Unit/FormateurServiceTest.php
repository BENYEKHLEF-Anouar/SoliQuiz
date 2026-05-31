<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\QCM;
use App\Models\Classe;
use App\Models\Tentative;
use App\Services\FormateurService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FormateurServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected FormateurService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FormateurService();
    }

    public function test_it_can_get_qcms_assigned_to_formateur()
    {
        $formateur = User::where('type_profil', 'formateur')->first();
        
        $qcm = QCM::create([
            'titre' => 'Test Qcm Formateur',
            'duree_minutes' => 10,
            'score_reussite' => 10,
            'formateur_id' => $formateur->id
        ]);

        $qcms = $this->service->getQcms($formateur);

        $this->assertGreaterThan(0, count($qcms));
        
        $found = collect($qcms)->firstWhere('id', $qcm->id);
        $this->assertNotNull($found);
        $this->assertEquals('Test Qcm Formateur', $found['title']);
    }

    public function test_it_can_get_cohorts_assigned_to_formateur()
    {
        $formateur = User::where('type_profil', 'formateur')->first();

        $classe = Classe::create([
            'nom' => 'TestClasseFormateur',
            'promotion' => '2026',
            'formateur_id' => $formateur->id
        ]);

        $cohorts = $this->service->getCohorts($formateur);

        $this->assertGreaterThan(0, count($cohorts));
    }

    public function test_it_can_get_cohort_students()
    {
        $formateur = User::where('type_profil', 'formateur')->first();

        $classe = Classe::create([
            'nom' => 'TestClasseEnroll',
            'promotion' => '2026',
            'formateur_id' => $formateur->id
        ]);

        $etudiant = User::create([
            'nom' => 'StudentNom',
            'prenom' => 'StudentPrenom',
            'email' => 'studenttest@solicode.ma',
            'password' => Hash::make('password'),
            'type_profil' => 'etudiant',
            'classe_id' => $classe->id
        ]);

        $students = $this->service->getCohortStudents($classe->id);

        $this->assertCount(1, $students);
        $this->assertEquals($etudiant->id, $students[0]['id']);
    }

    public function test_it_can_get_student_performance()
    {
        $etudiant = User::where('type_profil', 'etudiant')->first();
        
        $performance = $this->service->getStudentPerformance($etudiant->id);

        $this->assertEquals($etudiant->id, $performance['studentId']);
        $this->assertArrayHasKey('averageScore', $performance);
    }

    public function test_it_can_get_student_history()
    {
        $etudiant = User::where('type_profil', 'etudiant')->first();
        $formateur = User::where('type_profil', 'formateur')->first();

        $qcm = QCM::create([
            'titre' => 'HistQcmTest',
            'duree_minutes' => 10,
            'score_reussite' => 10,
            'formateur_id' => $formateur->id
        ]);

        $tentative = Tentative::create([
            'qcm_id' => $qcm->id,
            'etudiant_id' => $etudiant->id,
            'statut' => 'reussi',
            'score_obtenu' => 18,
            'date_fin' => now(),
        ]);

        $history = $this->service->getStudentHistory($etudiant->id);

        $this->assertGreaterThan(0, count($history));
        
        $found = collect($history)->firstWhere('id', $tentative->id);
        $this->assertNotNull($found);
        $this->assertEquals('HistQcmTest', $found['title']);
    }

    public function test_it_can_update_profile()
    {
        $formateur = User::where('type_profil', 'formateur')->first();

        $data = [
            'nom' => 'ModifiedNom',
            'prenom' => 'ModifiedPrenom',
        ];

        $updated = $this->service->updateProfile($formateur, $data);

        $this->assertEquals('ModifiedNom', $updated['nom']);
        $this->assertEquals('ModifiedPrenom', $updated['prenom']);
    }

    public function test_it_can_update_password()
    {
        $formateur = User::create([
            'nom' => 'FormateurPass',
            'prenom' => 'TestPass',
            'email' => 'formateurpass@solicode.ma',
            'password' => Hash::make('oldpassword'),
            'type_profil' => 'formateur',
        ]);

        $data = [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
        ];

        $this->service->updatePassword($formateur, $data);

        $this->assertTrue(Hash::check('newpassword123', $formateur->fresh()->password));
    }
}
