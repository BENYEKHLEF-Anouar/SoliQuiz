<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;
    protected $passwordResetService;

    public function __construct(
        AuthService $authService,
        \App\Services\PasswordResetService $passwordResetService
    ) {
        $this->authService = $authService;
        $this->passwordResetService = $passwordResetService;
    }

    /**
     * Authenticate a mobile user and return a token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $data = $this->authService->login(
            $request->email,
            $request->password,
            $request->device_name
        );

        return response()->json($data);
    }

    /**
     * Request a password reset for mobile user.
     */
    public function requestPasswordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $success = $this->passwordResetService->requestReset($request->email);

        if (!$success) {
            return response()->json([
                'message' => "Aucun utilisateur n'est enregistré avec cette adresse e-mail."
            ], 404);
        }

        return response()->json([
            'message' => "Votre demande de réinitialisation a été transmise à l'administrateur."
        ]);
    }

    /**
     * Revoke the current token.
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Déconnecté avec succès']);
    }
}
