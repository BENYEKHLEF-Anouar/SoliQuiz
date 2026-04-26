---
description: Phase 1 — Authentication & Role System
trigger: /phase1-auth
---

# 🔐 Phase 1 — Authentication & Roles

## Command
`/phase1-auth`

## Dependencies
- **Skill:** `ui-components`
- **Rules:** `rules/roles/access_control.md`

## Execution Steps

### 1. Authentication Scaffolding

**Install Laravel UI:**
```bash
composer require laravel/ui
php artisan ui:controllers
```

**Generate Auth Views:**
Create custom auth views in `resources/views/auth/`:
- `login.blade.php`
- `register.blade.php` (optional - admin creates users)

### 2. Login Controller

**`app/Http/Controllers/Auth/LoginController.php`:**
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Role-based redirect
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isFormateur()) {
                return redirect()->route('formateur.dashboard');
            } else {
                return redirect()->route('student.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Identifiants invalides.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
```

### 3. User Model Updates

**`app/Models/User.php`:**
```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'type_profil',
        'classe_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->type_profil === 'admin';
    }

    public function isFormateur(): bool
    {
        return $this->type_profil === 'formateur';
    }

    public function isEtudiant(): bool
    {
        return $this->type_profil === 'etudiant';
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }
}
```

### 4. Routes

**`routes/web.php`:**
```php
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\FormateurController;
use App\Http\Controllers\Web\StudentController;

// Public
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Protected
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // ... other admin routes
    });
    
    // Formateur routes
    Route::middleware(['role:formateur'])->prefix('formateur')->group(function () {
        Route::get('/dashboard', [FormateurController::class, 'dashboard'])->name('formateur.dashboard');
        // ... other formateur routes
    });
    
    // Student routes
    Route::middleware(['role:etudiant'])->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
        // ... other student routes
    });
});
```

### 5. Login View

**`resources/views/auth/login.blade.php`:**
```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SoliQuiz</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-md p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">SoliQuiz</h1>
            <p class="text-slate-500">Connexion à votre compte</p>
        </div>
        
        @if ($errors->any())
            <div class="bg-rose-50 text-rose-700 p-3 rounded-lg mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" required autofocus
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            
            <button type="submit" 
                    class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                Se connecter
            </button>
        </form>
    </div>
</body>
</html>
```

## Validation Checklist
- [ ] Login form displaying.
- [ ] Authentication working.
- [ ] Role-based redirects working.
- [ ] Logout working.
- [ ] Protected routes redirecting to login.
- [ ] Spatie middleware functional.

**Trace:** `Phase 1 — Authentication & Roles completed`
