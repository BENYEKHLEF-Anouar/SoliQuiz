<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\PasswordResetRequest;
use App\Services\PasswordResetService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PasswordResetServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PasswordResetService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PasswordResetService();
    }

    public function test_it_can_request_password_reset_for_existing_user()
    {
        $user = User::create([
            'nom' => 'TestReset',
            'prenom' => 'User',
            'email' => 'test-reset@solicode.ma',
            'password' => 'password',
            'type_profil' => 'etudiant'
        ]);

        $success = $this->service->requestReset($user->email);

        $this->assertTrue($success);
        $this->assertDatabaseHas('password_reset_requests', [
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    public function test_it_returns_false_for_non_existing_user()
    {
        $success = $this->service->requestReset('doesnotexist@solicode.ma');
        $this->assertFalse($success);
    }

    public function test_it_can_resolve_reset_request()
    {
        $user = User::create([
            'nom' => 'TestResolve',
            'prenom' => 'User',
            'email' => 'test-resolve@solicode.ma',
            'password' => 'password',
            'type_profil' => 'etudiant'
        ]);
        $request = PasswordResetRequest::create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $resolvedUser = $this->service->resolveRequest($request->id);

        $this->assertEquals($user->id, $resolvedUser->id);
        $this->assertDatabaseMissing('password_reset_requests', [
            'id' => $request->id,
        ]);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('123456', $resolvedUser->fresh()->password));
    }
}
