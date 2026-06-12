<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(\Illuminate\Http\Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ], [
            $this->username().'.required' => 'Votre identifiant est requis pour accéder à l\'écosystème.',
            'password.required' => 'La clé de sécurité est obligatoire pour cette session.',
        ]);
    }

    /**
     * Surcharge de la méthode authenticated pour forcer la redirection
     * vers le bon tableau de bord selon le rôle, en ignorant l'URL 'intended'
     * qui cause souvent des confusions dans le navigateur.
     */
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        $user->update(['derniere_connexion' => now()]);

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isFormateur()) {
            return redirect()->route('formateur.dashboard');
        }
        
        return redirect()->route('etudiant.dashboard');
    }
}
