<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    if ($user->isFormateur()) return redirect()->route('formateur.dashboard');
    return redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\Web\AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Web Admin Routes
    Route::get('/admin/utilisateurs', [\App\Http\Controllers\Web\AdminController::class, 'gestionUtilisateurs'])->name('admin.utilisateurs');
    Route::post('/admin/utilisateurs', [\App\Http\Controllers\Web\AdminController::class, 'storeUser'])->name('admin.utilisateurs.store');
    Route::put('/admin/utilisateurs/{id}', [\App\Http\Controllers\Web\AdminController::class, 'updateUser'])->name('admin.utilisateurs.update');
    Route::delete('/admin/utilisateurs/{id}', [\App\Http\Controllers\Web\AdminController::class, 'destroyUser'])->name('admin.utilisateurs.destroy');
    
    // Pedagogie / Seance Routes
    Route::get('/admin/pedagogie', [\App\Http\Controllers\Web\AdminController::class, 'pedagogie'])->name('admin.pedagogie');
    Route::post('/admin/pedagogie/seance', [\App\Http\Controllers\Web\AdminController::class, 'storeSeance'])->name('admin.pedagogie.seance.store');
    Route::delete('/admin/pedagogie/seance/{id}', [\App\Http\Controllers\Web\AdminController::class, 'destroySeance'])->name('admin.pedagogie.seance.destroy');
    Route::post('/admin/pedagogie/seance/{id}/ua', [\App\Http\Controllers\Web\AdminController::class, 'storeUA'])->name('admin.pedagogie.ua.store');
    Route::delete('/admin/pedagogie/ua/{id}', [\App\Http\Controllers\Web\AdminController::class, 'destroyUA'])->name('admin.pedagogie.ua.destroy');
    Route::post('/admin/pedagogie/ua/{id}/competence', [\App\Http\Controllers\Web\AdminController::class, 'storeCompetence'])->name('admin.pedagogie.competence.store');
    Route::delete('/admin/pedagogie/competence/{id}', [\App\Http\Controllers\Web\AdminController::class, 'destroyCompetence'])->name('admin.pedagogie.competence.destroy');
    
    // Classes Routes
    Route::get('/admin/classes', [\App\Http\Controllers\Web\AdminController::class, 'gestionClasses'])->name('admin.classes');
    Route::get('/admin/classes/{id}', [\App\Http\Controllers\Web\AdminController::class, 'showClasse'])->name('admin.classes.show');
    Route::post('/admin/classes', [\App\Http\Controllers\Web\AdminController::class, 'storeClasse'])->name('admin.classes.store');
    Route::delete('/admin/classes/{id}', [\App\Http\Controllers\Web\AdminController::class, 'destroyClasse'])->name('admin.classes.destroy');
    Route::post('/admin/classes/{id}/formateur', [\App\Http\Controllers\Web\AdminController::class, 'assignFormateur'])->name('admin.classes.assign');
    Route::post('/admin/classes/{id}/etudiants', [\App\Http\Controllers\Web\AdminController::class, 'addStudentToClasse'])->name('admin.classes.students.add');
    Route::delete('/admin/classes/{id}/etudiants/{userId}', [\App\Http\Controllers\Web\AdminController::class, 'removeStudentFromClasse'])->name('admin.classes.students.remove');

    // Web Formateur Routes
    Route::get('/formateur/dashboard', [\App\Http\Controllers\Web\FormateurController::class, 'dashboard'])->name('formateur.dashboard');
    Route::get('/formateur/bibliotheque', [\App\Http\Controllers\Web\FormateurController::class, 'bibliotheque'])->name('formateur.bibliotheque');
    Route::get('/formateur/qcm/create', [\App\Http\Controllers\Web\FormateurController::class, 'createQcm'])->name('formateur.qcm.create');
    Route::post('/formateur/qcm', [\App\Http\Controllers\Web\FormateurController::class, 'storeQcm'])->name('formateur.qcm.store');
    Route::delete('/formateur/qcm/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroyQcm'])->name('formateur.qcm.destroy');
    Route::get('/formateur/resultats', [\App\Http\Controllers\Web\FormateurController::class, 'resultatsCohorte'])->name('formateur.resultats');
    
    // Formateur Pedagogie Management
    Route::get('/formateur/pedagogie', [\App\Http\Controllers\Web\FormateurController::class, 'pedagogie'])->name('formateur.pedagogie');
    Route::post('/formateur/pedagogie/seance', [\App\Http\Controllers\Web\FormateurController::class, 'storeSeance'])->name('formateur.pedagogie.seance.store');
    Route::delete('/formateur/pedagogie/seance/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroySeance'])->name('formateur.pedagogie.seance.destroy');
    Route::post('/formateur/pedagogie/ua', [\App\Http\Controllers\Web\FormateurController::class, 'storeUA'])->name('formateur.pedagogie.ua.store');
    Route::delete('/formateur/pedagogie/ua/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroyUA'])->name('formateur.pedagogie.ua.destroy');
    Route::post('/formateur/pedagogie/competence', [\App\Http\Controllers\Web\FormateurController::class, 'storeCompetence'])->name('formateur.pedagogie.competence.store');
    Route::delete('/formateur/pedagogie/competence/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroyCompetence'])->name('formateur.pedagogie.competence.destroy');
    
    // Web Student Routes
    Route::get('/student/dashboard', [\App\Http\Controllers\Web\StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/bibliotheque', [\App\Http\Controllers\Web\StudentController::class, 'bibliotheque'])->name('student.bibliotheque');
    Route::get('/student/qcm/{id}', [\App\Http\Controllers\Web\StudentController::class, 'passation'])->name('student.passation');
    Route::post('/student/qcm/{id}', [\App\Http\Controllers\Web\StudentController::class, 'submitQcm'])->name('student.qcm.submit');
    Route::get('/student/qcm/{id}/resultats', [\App\Http\Controllers\Web\StudentController::class, 'resultats'])->name('student.resultats');

    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Web\ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Auth::routes();
