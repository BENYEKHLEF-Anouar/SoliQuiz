<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected AuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AuthService();
    }

    public function test_it_can_login_with_correct_credentials()
    {
        $user = User::create([
            'nom' => 'Auth',
            'prenom' => 'Test',
            'email' => 'testauth@solicode.ma',
            'password' => Hash::make('password123'),
            'type_profil' => 'etudiant',
        ]);

        $result = $this->service->login('testauth@solicode.ma', 'password123', 'TestDevice');

        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('user', $result);
        $this->assertEquals($user->id, $result['user']['id']);
    }

    public function test_it_throws_exception_with_incorrect_credentials()
    {
        $user = User::create([
            'nom' => 'Auth2',
            'prenom' => 'Test2',
            'email' => 'testauth2@solicode.ma',
            'password' => Hash::make('password123'),
            'type_profil' => 'etudiant',
        ]);

        $this->expectException(ValidationException::class);

        $this->service->login('testauth2@solicode.ma', 'wrongpassword', 'TestDevice');
    }

    public function test_it_can_logout_user()
    {
        $user = User::create([
            'nom' => 'Auth3',
            'prenom' => 'Test3',
            'email' => 'testauth3@solicode.ma',
            'password' => Hash::make('password123'),
            'type_profil' => 'etudiant',
        ]);
        $token = $user->createToken('TestDevice');
        
        $user->withAccessToken($token->accessToken);

        $this->service->logout($user);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    }
}
