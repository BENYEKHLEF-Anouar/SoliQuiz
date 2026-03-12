<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UserService();
    }

    public function test_it_can_get_all_users()
    {
        $result = $this->service->paginate();
        $this->assertGreaterThan(0, $result->total());
    }

    public function test_it_can_filter_users_by_search()
    {
        $result = $this->service->paginate(15, 'Benali');

        $this->assertEquals(1, $result->total());

        $firstUser = collect($result->items())->first();
        $this->assertStringContainsString('Benali', $firstUser->nom);
    }

    public function test_it_can_filter_users_by_profil()
    {
        // Pick a profile that exists in your seeded data
        $profil = 'formateur';

        $result = $this->service->paginate(15, null, $profil);

        $this->assertGreaterThan(0, $result->total());

        // Ensure every returned user belongs to the selected profile
        foreach ($result->items() as $user) {
            $this->assertEquals($profil, $user->type_profil);
        }
    }

    public function test_it_can_create_a_user()
    {
        $data = [
            'nom' => 'New User Test',
            'prenom' => 'Test',
            'email' => 'newuser@solicode.ma',
            'password' => 'password',
            'type_profil' => 'etudiant',
        ];

        $user = $this->service->create($data);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nom' => 'New User Test',
        ]);
    }

    public function test_it_can_update_a_user()
    {
        $user = User::first();

        $updatedData = [
            'nom' => 'Updated User Test',
            'prenom' => 'Updated content',
        ];

        $this->service->update($user, $updatedData);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nom' => 'Updated User Test',
            'prenom' => 'Updated content',
        ]);
    }

    public function test_it_can_delete_a_user()
    {
        $user = User::first();

        $this->service->delete($user);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
