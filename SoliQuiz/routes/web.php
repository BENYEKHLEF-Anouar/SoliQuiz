<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('public.landing');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Auth::routes();

    // Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    
    // User Management
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store']);
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy']);
    Route::post('/users/mass-assign', [App\Http\Controllers\UserController::class, 'massAssignClass']);
    
    // Pedagogie CRUD
    Route::post('/seances', [App\Http\Controllers\PedagogieController::class, 'storeSeance']);
    Route::put('/seances/{seance}', [App\Http\Controllers\PedagogieController::class, 'updateSeance']);
    Route::delete('/seances/{seance}', [App\Http\Controllers\PedagogieController::class, 'destroySeance']);
    
    Route::post('/uas', [App\Http\Controllers\PedagogieController::class, 'storeUA']);
    Route::put('/uas/{ua}', [App\Http\Controllers\PedagogieController::class, 'updateUA']);
    Route::delete('/uas/{ua}', [App\Http\Controllers\PedagogieController::class, 'destroyUA']);
    
    Route::post('/competences', [App\Http\Controllers\PedagogieController::class, 'storeCompetence']);
    Route::put('/competences/{competence}', [App\Http\Controllers\PedagogieController::class, 'updateCompetence']);
    Route::delete('/competences/{competence}', [App\Http\Controllers\PedagogieController::class, 'destroyCompetence']);

    // QCM Supervision
    Route::patch('/qcms/{qcm}/toggle', function(\App\Models\QCM $qcm, \App\Services\QcmService $service) {
        $service->togglePublication($qcm);
        return response()->json(['message' => 'Statut du QCM mis à jour']);
    });
    Route::delete('/qcms/{qcm}', function(\App\Models\QCM $qcm) {
        $qcm->delete();
        return response()->json(['message' => 'QCM supprimé définitivement']);
    });
});

// Formateur Routes
Route::middleware(['auth', 'role:formateur'])->prefix('formateur')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\FormateurController::class, 'index'])->name('formateur.dashboard');
    
    // QCM Studio
    Route::get('/qcms', [App\Http\Controllers\FormateurController::class, 'qcms']);
    Route::get('/qcms/{id}', [App\Http\Controllers\FormateurController::class, 'qcmDetails']);
    Route::post('/qcms', [App\Http\Controllers\FormateurController::class, 'storeQcm']);
    Route::put('/qcms/{qcm}', [App\Http\Controllers\FormateurController::class, 'updateQcm']);
    
    // Results
    Route::get('/resultats', [App\Http\Controllers\FormateurController::class, 'results']);
});

// Redirect authenticated users
Route::get('/dashboard', function() {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->user()->hasRole('formateur')) {
        return redirect()->route('formateur.dashboard');
    }
    return redirect('/');
})->middleware('auth')->name('dashboard');

Route::get('/home', function() {
    return redirect('/dashboard');
});
