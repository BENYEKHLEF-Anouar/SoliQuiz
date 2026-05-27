<?php

namespace App\Services;

use App\Models\User;
use App\Models\PasswordResetRequest;
use Illuminate\Support\Facades\Hash;

class PasswordResetService
{
    public function requestReset(string $email): bool
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return false;
        }

        PasswordResetRequest::firstOrCreate([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        return true;
    }

    public function resolveRequest(int $requestId): User
    {
        $request = PasswordResetRequest::findOrFail($requestId);
        $user = $request->user;

        $user->password = Hash::make('123456');
        $user->save();

        $request->delete();

        return $user;
    }
}
