---
description: API endpoints for SoliQuiz Mobile (NativePHP)
trigger: /api-mobile
---

# 📱 API Mobile Workflow

## Command
`/api-mobile`

## Dependencies
- **Skill:** `passation-service`, `qcm-engine`
- **Rules:** `rules/roles/access_control.md`

## Execution Steps

### 1. API Routes

**`routes/api.php`:**
```php
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QcmController;
use App\Http\Controllers\Api\TentativeController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/login', [AuthController::class, 'login']);

// Protected
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // QCMs
    Route::get('/qcms', [QcmController::class, 'index']);
    Route::get('/qcms/{id}', [QcmController::class, 'show']);
    
    // Passation
    Route::post('/qcms/{id}/start', [TentativeController::class, 'start']);
    Route::post('/tentatives/{id}/submit', [TentativeController::class, 'submit']);
    Route::get('/tentatives/{id}/results', [TentativeController::class, 'results']);
});
```

### 2. Auth Controller

**`app/Http/Controllers/Api/AuthController.php`:**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Identifiants invalides'
            ], 401);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'type_profil' => $user->type_profil,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnecté']);
    }
}
```

### 3. QCM Controller

**`app/Http/Controllers/Api/QcmController.php`:**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Qcm;
use App\Services\QcmPublicService;
use Illuminate\Http\Request;

class QcmController extends Controller
{
    public function __construct(
        private QcmPublicService $qcmService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        
        $qcms = $this->qcmService->getQcmsDisponibles(
            $user->id, 
            $user->classe_id
        );

        return response()->json([
            'a_faire' => $qcms['a_faire'],
            'en_cours' => $qcms['en_cours'],
            'termines' => $qcms['termines'],
        ]);
    }

    public function show(Request $request, int $id)
    {
        $user = $request->user();
        $qcm = $this->qcmService->getQcmForPassation($id);
        
        // Verify QCM is for user's class
        if ($qcm->classe_id !== $user->classe_id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        return response()->json([
            'id' => $qcm->id,
            'titre' => $qcm->titre,
            'duree_minutes' => $qcm->duree_minutes,
            'seuil_reussite' => $qcm->seuil_reussite,
            'questions_count' => $qcm->questions_count,
        ]);
    }
}
```

### 4. Tentative Controller

**`app/Http/Controllers/Api/TentativeController.php`:**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Qcm;
use App\Models\Tentative;
use App\Services\PassationService;
use Illuminate\Http\Request;

class TentativeController extends Controller
{
    public function __construct(
        private PassationService $passationService
    ) {}

    public function start(Request $request, int $qcmId)
    {
        $user = $request->user();
        $qcm = Qcm::findOrFail($qcmId);

        // Verify QCM is published and for user's class
        if (!$qcm->est_publie || $qcm->classe_id !== $user->classe_id) {
            return response()->json(['message' => 'QCM non disponible'], 403);
        }

        $tentative = $this->passationService->demarrer($qcm, $user);
        $questions = $qcm->questions()->with('options:id,question_id,texte')->get();

        return response()->json([
            'tentative_id' => $tentative->id,
            'time_remaining_seconds' => $tentative->getTimeRemaining(),
            'questions' => $questions,
        ]);
    }

    public function submit(Request $request, int $tentativeId)
    {
        $user = $request->user();
        
        $tentative = Tentative::where('id', $tentativeId)
            ->where('user_id', $user->id)
            ->where('statut', 'en_cours')
            ->firstOrFail();

        $reponses = $request->input('reponses', []);
        $this->passationService->soumettre($tentative, $reponses);

        return response()->json([
            'message' => 'QCM soumis avec succès',
            'score' => $tentative->score_obtenu,
            'passed' => $tentative->score_obtenu >= $tentative->qcm->seuil_reussite,
        ]);
    }

    public function results(Request $request, int $tentativeId)
    {
        $user = $request->user();
        
        $tentative = Tentative::where('id', $tentativeId)
            ->where('user_id', $user->id)
            ->where('statut', 'termine')
            ->with(['qcm.questions.options', 'reponses'])
            ->firstOrFail();

        return response()->json([
            'tentative' => $tentative,
            'score' => $tentative->score_obtenu,
            'seuil_reussite' => $tentative->qcm->seuil_reussite,
            'time_spent_minutes' => $tentative->getTimeSpent(),
        ]);
    }
}
```

### 5. Response Format

All API responses follow this structure:
```json
{
    "success": true,
    "data": { ... },
    "message": "..."
}
```

For errors:
```json
{
    "success": false,
    "message": "Error description",
    "errors": { ... }
}
```

## Validation Checklist
- [ ] Login endpoint returning token.
- [ ] Protected endpoints requiring token.
- [ ] QCM list returning correct buckets.
- [ ] Start attempt returning questions.
- [ ] Submit attempt calculating score.
- [ ] Results endpoint returning detailed data.
- [ ] Error handling consistent.

**Trace:** `API Mobile Workflow executed`
