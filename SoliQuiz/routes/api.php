<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\QcmController;
use App\Http\Controllers\Api\FormateurController;

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

// Student endpoints (hardcoded student ID 4)
Route::prefix('student')->group(function () {
    Route::get('/profile', [StudentController::class, 'profile']);
    Route::get('/scores', [StudentController::class, 'scores']);
    Route::get('/evaluations', [StudentController::class, 'evaluations']);
    Route::get('/notifications', [StudentController::class, 'notifications']);
    Route::get('/history', [StudentController::class, 'history']);
});

// QCM endpoints
Route::prefix('qcm')->group(function () {
    Route::get('/{id}', [QcmController::class, 'show']);
    Route::get('/{id}/questions', [QcmController::class, 'questions']);
    Route::get('/{id}/result', [QcmController::class, 'result']);
});

// Formateur endpoints (hardcoded formateur ID 2)
Route::prefix('formateur')->group(function () {
    Route::get('/profile', [FormateurController::class, 'profile']);
    Route::get('/qcms', [FormateurController::class, 'qcms']);
    Route::get('/qcms/{qcmId}/results', [FormateurController::class, 'qcmResults']);
    Route::get('/cohorts', [FormateurController::class, 'cohorts']);
    Route::get('/cohorts/{cohortId}/students', [FormateurController::class, 'cohortStudents']);
    Route::get('/students/{studentId}/performance', [FormateurController::class, 'studentPerformance']);
    Route::get('/students/{studentId}/history', [FormateurController::class, 'studentHistory']);
});