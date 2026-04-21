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
    
    // Group: Admin Only
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function() {
        Route::get('/dashboard', [\App\Http\Controllers\Web\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/qcms', [\App\Http\Controllers\Web\AdminController::class, 'indexQcms'])->name('qcms');
        
        Route::get('/utilisateurs', [\App\Http\Controllers\Web\AdminController::class, 'gestionUtilisateurs'])->name('utilisateurs');
        Route::post('/utilisateurs', [\App\Http\Controllers\Web\AdminController::class, 'storeUser'])->name('utilisateurs.store');
        Route::put('/utilisateurs/{id}', [\App\Http\Controllers\Web\AdminController::class, 'updateUser'])->name('utilisateurs.update');
        Route::delete('/utilisateurs/{id}', [\App\Http\Controllers\Web\AdminController::class, 'destroyUser'])->name('utilisateurs.destroy');
        
        Route::get('/pedagogie', [\App\Http\Controllers\Web\AdminController::class, 'pedagogie'])->name('pedagogie');
        Route::post('/pedagogie/seance', [\App\Http\Controllers\Web\AdminController::class, 'storeSeance'])->name('pedagogie.seance.store');
        Route::get('/pedagogie/seance/{id}/edit', [\App\Http\Controllers\Web\AdminController::class, 'editSeance'])->name('pedagogie.seance.edit');
        Route::put('/pedagogie/seance/{id}', [\App\Http\Controllers\Web\AdminController::class, 'updateSeance'])->name('pedagogie.seance.update');
        Route::delete('/pedagogie/seance/{id}', [\App\Http\Controllers\Web\AdminController::class, 'destroySeance'])->name('pedagogie.seance.destroy');
        Route::post('/pedagogie/seance/{id}/ua', [\App\Http\Controllers\Web\AdminController::class, 'storeUA'])->name('pedagogie.ua.store');
        Route::get('/pedagogie/ua/{id}/edit', [\App\Http\Controllers\Web\AdminController::class, 'editUA'])->name('pedagogie.ua.edit');
        Route::put('/pedagogie/ua/{id}', [\App\Http\Controllers\Web\AdminController::class, 'updateUA'])->name('pedagogie.ua.update');
        Route::delete('/pedagogie/ua/{id}', [\App\Http\Controllers\Web\AdminController::class, 'destroyUA'])->name('pedagogie.ua.destroy');
        Route::post('/pedagogie/ua/{id}/competence', [\App\Http\Controllers\Web\AdminController::class, 'storeCompetence'])->name('pedagogie.competence.store');
        Route::get('/pedagogie/competence/{id}/edit', [\App\Http\Controllers\Web\AdminController::class, 'editCompetence'])->name('pedagogie.competence.edit');
        Route::put('/pedagogie/competence/{id}', [\App\Http\Controllers\Web\AdminController::class, 'updateCompetence'])->name('pedagogie.competence.update');
        Route::delete('/pedagogie/competence/{id}', [\App\Http\Controllers\Web\AdminController::class, 'destroyCompetence'])->name('pedagogie.competence.destroy');
        
        Route::get('/classes', [\App\Http\Controllers\Web\AdminController::class, 'gestionClasses'])->name('classes');
        Route::get('/classes/{id}', [\App\Http\Controllers\Web\AdminController::class, 'showClasse'])->name('classes.show');
        Route::post('/classes', [\App\Http\Controllers\Web\AdminController::class, 'storeClasse'])->name('classes.store');
        Route::delete('/classes/{id}', [\App\Http\Controllers\Web\AdminController::class, 'destroyClasse'])->name('classes.destroy');
        Route::post('/classes/{id}/formateur', [\App\Http\Controllers\Web\AdminController::class, 'assignFormateur'])->name('classes.assign');
        Route::post('/classes/{id}/etudiants', [\App\Http\Controllers\Web\AdminController::class, 'addStudentToClasse'])->name('classes.students.add');
        Route::delete('/classes/{id}/etudiants/{userId}', [\App\Http\Controllers\Web\AdminController::class, 'removeStudentFromClasse'])->name('classes.students.remove');
    });

    // Group: Formateur Only
    Route::middleware(['role:formateur'])->prefix('formateur')->name('formateur.')->group(function() {
        Route::get('/dashboard', [\App\Http\Controllers\Web\FormateurController::class, 'dashboard'])->name('dashboard');
        Route::get('/bibliotheque', [\App\Http\Controllers\Web\FormateurController::class, 'bibliotheque'])->name('bibliotheque');
        Route::get('/qcm/create', [\App\Http\Controllers\Web\FormateurController::class, 'createQcm'])->name('qcm.create');
        Route::post('/qcm', [\App\Http\Controllers\Web\FormateurController::class, 'storeQcm'])->name('qcm.store');
        Route::get('/qcm/{id}/edit', [\App\Http\Controllers\Web\FormateurController::class, 'editQcm'])->name('qcm.edit');
        Route::put('/qcm/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'updateQcm'])->name('qcm.update');
        Route::delete('/qcm/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroyQcm'])->name('qcm.destroy');
        Route::patch('/qcm/{id}/toggle', [\App\Http\Controllers\Web\FormateurController::class, 'toggleQcmStatus'])->name('qcm.toggle');
        Route::patch('/qcm/{id}/close', [\App\Http\Controllers\Web\FormateurController::class, 'closeQcm'])->name('qcm.close');
        Route::get('/resultats', [\App\Http\Controllers\Web\FormateurController::class, 'resultatsCohorte'])->name('resultats');
        
        Route::get('/pedagogie', [\App\Http\Controllers\Web\FormateurController::class, 'pedagogie'])->name('pedagogie');
        Route::post('/pedagogie/seance', [\App\Http\Controllers\Web\FormateurController::class, 'storeSeance'])->name('pedagogie.seance.store');
        Route::get('/pedagogie/seance/{id}/edit', [\App\Http\Controllers\Web\FormateurController::class, 'editSeance'])->name('pedagogie.seance.edit');
        Route::put('/pedagogie/seance/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'updateSeance'])->name('pedagogie.seance.update');
        Route::delete('/pedagogie/seance/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroySeance'])->name('pedagogie.seance.destroy');
        Route::post('/pedagogie/ua', [\App\Http\Controllers\Web\FormateurController::class, 'storeUA'])->name('pedagogie.ua.store');
        Route::get('/pedagogie/ua/{id}/edit', [\App\Http\Controllers\Web\FormateurController::class, 'editUA'])->name('pedagogie.ua.edit');
        Route::put('/pedagogie/ua/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'updateUA'])->name('pedagogie.ua.update');
        Route::delete('/pedagogie/ua/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroyUA'])->name('pedagogie.ua.destroy');
        Route::post('/pedagogie/competence', [\App\Http\Controllers\Web\FormateurController::class, 'storeCompetence'])->name('pedagogie.competence.store');
        Route::get('/pedagogie/competence/{id}/edit', [\App\Http\Controllers\Web\FormateurController::class, 'editCompetence'])->name('pedagogie.competence.edit');
        Route::put('/pedagogie/competence/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'updateCompetence'])->name('pedagogie.competence.update');
        Route::delete('/pedagogie/competence/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroyCompetence'])->name('pedagogie.competence.destroy');
    });

    // Group: Student Only
    Route::middleware(['role:etudiant'])->prefix('student')->name('student.')->group(function() {
        Route::get('/dashboard', [\App\Http\Controllers\Web\StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/bibliotheque', [\App\Http\Controllers\Web\StudentController::class, 'bibliotheque'])->name('bibliotheque');
        Route::get('/qcm/{id}', [\App\Http\Controllers\Web\StudentController::class, 'passation'])->name('passation');
        Route::post('/qcm/{id}', [\App\Http\Controllers\Web\StudentController::class, 'submitQcm'])->name('qcm.submit');
        Route::get('/qcm/{id}/resultats', [\App\Http\Controllers\Web\StudentController::class, 'resultats'])->name('resultats');
    });

    // Shared Profile Routes
    Route::get('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Web\ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Auth::routes(['register' => false]);
