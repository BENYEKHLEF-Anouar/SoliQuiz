<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Classe;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClasseEnrollmentTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected Classe $classe;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('type_profil', 'admin')->first();
        if (!$this->admin) {
            $this->admin = User::create([
                'nom' => 'System',
                'prenom' => 'Admin',
                'email' => 'admin.test@soliquiz.com',
                'password' => 'password',
                'type_profil' => 'admin'
            ]);
            $this->admin->assignRole('admin');
        }

        $this->classe = Classe::first() ?: Classe::create([
            'nom' => 'Test Classe',
            'promotion' => '2026'
        ]);
    }

    public function test_admin_can_bulk_add_students()
    {
        $student1 = User::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@test.com',
            'password' => 'password',
            'type_profil' => 'etudiant',
            'classe_id' => null
        ]);
        $student1->assignRole('etudiant');

        $student2 = User::create([
            'nom' => 'Martin',
            'prenom' => 'Alice',
            'email' => 'alice.martin@test.com',
            'password' => 'password',
            'type_profil' => 'etudiant',
            'classe_id' => null
        ]);
        $student2->assignRole('etudiant');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.classes.students.bulk-add', $this->classe->id), [
                'user_ids' => [$student1->id, $student2->id]
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('users', [
            'id' => $student1->id,
            'classe_id' => $this->classe->id
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $student2->id,
            'classe_id' => $this->classe->id
        ]);
    }

    public function test_admin_can_import_students()
    {
        $existingStudent = User::create([
            'nom' => 'Bernard',
            'prenom' => 'Marc',
            'email' => 'marc.bernard@test.com',
            'password' => 'password',
            'type_profil' => 'etudiant',
            'classe_id' => null
        ]);
        $existingStudent->assignRole('etudiant');

        $newStudentEmail = 'new.imported.student@test.com';
        $newStudentName = 'Leroy';
        $newStudentFirstName = 'Lucie';

        $importData = "{$newStudentFirstName};{$newStudentName};{$newStudentEmail}\n{$existingStudent->email}";

        $response = $this->actingAs($this->admin)
            ->post(route('admin.classes.students.import', $this->classe->id), [
                'import_data' => $importData
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $existingStudent->id,
            'classe_id' => $this->classe->id
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $newStudentEmail,
            'prenom' => $newStudentFirstName,
            'nom' => $newStudentName,
            'type_profil' => 'etudiant',
            'classe_id' => $this->classe->id
        ]);
    }

    public function test_can_search_classes_by_formateur_name()
    {
        $formateur = User::create([
            'nom' => 'El Amrani',
            'prenom' => 'Sarah',
            'email' => 'sarah.elamrani@soliquiz.com',
            'password' => 'password',
            'type_profil' => 'formateur'
        ]);

        $classe = Classe::create([
            'nom' => 'DW101 Search Test',
            'promotion' => '2026',
            'formateur_id' => $formateur->id
        ]);

        $this->actingAs($this->admin);

        // Search by prenom
        $response = $this->get(route('admin.classes.search') . '?search=Sarah');
        $response->assertJsonFragment([
            'nom' => 'DW101 Search Test'
        ]);

        // Search by nom
        $response2 = $this->get(route('admin.classes.search') . '?search=Amrani');
        $response2->assertJsonFragment([
            'nom' => 'DW101 Search Test'
        ]);
    }
}
