<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
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

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);

    // Student endpoints - etudiant and admin role can access
    Route::prefix('student')->middleware('role:etudiant,admin')->group(function () {
        Route::get('/profile', [StudentController::class, 'profile']);
        Route::get('/scores', [StudentController::class, 'scores']);
        Route::get('/evaluations', [StudentController::class, 'evaluations']);
        Route::get('/notifications', [StudentController::class, 'notifications']);
        Route::get('/history', [StudentController::class, 'history']);
    });

    // QCM endpoints - etudiant and admin role can access
    Route::prefix('qcm')->middleware('role:etudiant,admin')->group(function () {
        Route::get('/{id}', [QcmController::class, 'show']);
        Route::get('/{id}/questions', [QcmController::class, 'questions']);
        Route::get('/{id}/result', [QcmController::class, 'result']);
    });

    // Formateur endpoints - formulaire role only (admins can also access via their bypass)
    Route::prefix('formateur')->group(function () {
        Route::get('/profile', [FormateurController::class, 'profile']);
        Route::get('/qcms', [FormateurController::class, 'qcms']);
        Route::get('/qcms/{qcmId}/results', [FormateurController::class, 'qcmResults']);
        Route::get('/cohorts', [FormateurController::class, 'cohorts']);
        Route::get('/cohorts/{cohortId}/students', [FormateurController::class, 'cohortStudents']);
        Route::get('/students/{studentId}/performance', [FormateurController::class, 'studentPerformance']);
        Route::get('/students/{studentId}/history', [FormateurController::class, 'studentHistory']);
    });

});