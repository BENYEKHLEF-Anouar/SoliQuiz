<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EtudiantController;
use App\Http\Controllers\Api\QcmController;
use App\Http\Controllers\Api\FormateurController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/chatbot/chat', [\App\Http\Controllers\Api\ChatbotController::class, 'chat']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);

    // Student endpoints - etudiant and admin role can access
    Route::prefix('etudiant')->middleware('role:etudiant,admin')->group(function () {
        Route::get('/profile', [EtudiantController::class, 'profile']);
        Route::put('/profile', [EtudiantController::class, 'updateProfile']);
        Route::put('/profile/password', [EtudiantController::class, 'updatePassword']);
        Route::get('/scores', [EtudiantController::class, 'scores']);
        Route::get('/evaluations', [EtudiantController::class, 'evaluations']);
        Route::get('/notifications', [EtudiantController::class, 'notifications']);
        Route::get('/history', [EtudiantController::class, 'history']);
        Route::get('/bibliotheque', [EtudiantController::class, 'bibliotheque']);
    });



    // QCM endpoints - etudiant and admin role can access
    Route::prefix('qcm')->middleware('role:etudiant,admin')->group(function () {
        Route::get('/{id}', [QcmController::class, 'show']);
        Route::get('/{id}/questions', [QcmController::class, 'questions']);
        Route::get('/{id}/result', [QcmController::class, 'result']);
        Route::post('/{id}/start', [QcmController::class, 'start']);
        Route::post('/{id}/submit', [QcmController::class, 'submit']);
    });

    // Formateur endpoints - formulaire role only (admins can also access via their bypass)
    Route::prefix('formateur')->middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [FormateurController::class, 'profile']);
        Route::put('/profile', [FormateurController::class, 'updateProfile']);
        Route::put('/profile/password', [FormateurController::class, 'updatePassword']);
        Route::get('/qcms', [FormateurController::class, 'qcms']);
        Route::get('/results', [FormateurController::class, 'results']);
        Route::get('/pedagogie', [FormateurController::class, 'pedagogie']);
        Route::get('/qcms/{qcmId}/results', [FormateurController::class, 'qcmResults']);
        Route::get('/cohorts', [FormateurController::class, 'cohorts']);
        Route::get('/cohorts/{cohortId}/students', [FormateurController::class, 'cohortStudents']);
        Route::get('/students/{studentId}/performance', [FormateurController::class, 'studentPerformance']);
        Route::get('/students/{studentId}/history', [FormateurController::class, 'studentHistory']);
    });

});