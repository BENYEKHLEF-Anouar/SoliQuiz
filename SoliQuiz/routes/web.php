<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    if ($user->isFormateur()) return redirect()->route('formateur.dashboard');
    return redirect()->route('etudiant.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/api/check-code', function(Illuminate\Http\Request $request) {
    $code = $request->query('code');
    $type = $request->query('type');
    if ($type === 'ua') {
        $exists = \App\Models\UniteApprentissage::where('code', $code)->exists();
    } else {
        $exists = \App\Models\Competence::where('code', $code)->exists();
    }
    return response()->json(['exists' => $exists]);
})->name('api.check-code');

Route::middleware(['auth'])->group(function () {
    
    // Group: Admin Only
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function() {
        Route::get('/dashboard', [\App\Http\Controllers\Web\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/resultats', [\App\Http\Controllers\Web\AdminController::class, 'resultats'])->name('resultats');
        Route::get('/qcms', [\App\Http\Controllers\Web\AdminController::class, 'indexQcms'])->name('qcms');
        Route::get('/qcms/search', [\App\Http\Controllers\Web\AdminController::class, 'searchQcms'])->name('qcms.search');
        
        Route::get('/utilisateurs', [\App\Http\Controllers\Web\AdminController::class, 'gestionUtilisateurs'])->name('utilisateurs');
        Route::get('/utilisateurs/search', [\App\Http\Controllers\Web\AdminController::class, 'searchUsers'])->name('utilisateurs.search');
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
        
        Route::get('/classes', [\App\Http\Controllers\Web\Admin\ClasseController::class, 'index'])->name('classes');
        Route::get('/classes/search', [\App\Http\Controllers\Web\Admin\ClasseController::class, 'search'])->name('classes.search');
        Route::get('/classes/{id}', [\App\Http\Controllers\Web\Admin\ClasseController::class, 'show'])->name('classes.show');
        Route::post('/classes', [\App\Http\Controllers\Web\Admin\ClasseController::class, 'store'])->name('classes.store');
        Route::delete('/classes/{classe}', [\App\Http\Controllers\Web\Admin\ClasseController::class, 'destroy'])->name('classes.destroy');
        Route::post('/classes/{id}/formateur', [\App\Http\Controllers\Web\Admin\ClasseController::class, 'assignFormateur'])->name('classes.assign');
        Route::post('/classes/{classe}/etudiants', [\App\Http\Controllers\Web\Admin\ClasseController::class, 'addStudent'])->name('classes.students.add');
        Route::post('/classes/{classe}/etudiants/bulk', [\App\Http\Controllers\Web\Admin\ClasseController::class, 'bulkAddStudents'])->name('classes.students.bulk-add');
        Route::post('/classes/{classe}/etudiants/import', [\App\Http\Controllers\Web\Admin\ClasseController::class, 'importStudents'])->name('classes.students.import');
        Route::delete('/classes/{id}/etudiants/{userId}', [\App\Http\Controllers\Web\Admin\ClasseController::class, 'removeStudent'])->name('classes.students.remove');
    });

    // Group: Formateur and Admin
    Route::middleware(['role:formateur,admin'])->prefix('formateur')->name('formateur.')->group(function() {
        Route::get('/dashboard', [\App\Http\Controllers\Web\FormateurController::class, 'dashboard'])->name('dashboard');
        Route::get('/bibliotheque', [\App\Http\Controllers\Web\FormateurController::class, 'bibliotheque'])->name('bibliotheque');
        Route::get('/bibliotheque/search', [\App\Http\Controllers\Web\FormateurController::class, 'searchBibliotheque'])->name('bibliotheque.search');
        Route::get('/qcm/create', [\App\Http\Controllers\Web\FormateurController::class, 'createQcm'])->name('qcm.create');
        Route::post('/qcm', [\App\Http\Controllers\Web\FormateurController::class, 'storeQcm'])->name('qcm.store');
        Route::get('/qcm/{id}/edit', [\App\Http\Controllers\Web\FormateurController::class, 'editQcm'])->name('qcm.edit');
        Route::put('/qcm/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'updateQcm'])->name('qcm.update');
        Route::delete('/qcm/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroyQcm'])->name('qcm.destroy');
        Route::patch('/qcm/{id}/toggle', [\App\Http\Controllers\Web\FormateurController::class, 'toggleQcmStatus'])->name('qcm.toggle');
        Route::patch('/qcm/{id}/close', [\App\Http\Controllers\Web\FormateurController::class, 'closeQcm'])->name('qcm.close');
        Route::post('/qcm/{id}/duplicate', [\App\Http\Controllers\Web\FormateurController::class, 'duplicateQcm'])->name('qcm.duplicate');
        Route::post('/qcm/generate-ai', [\App\Http\Controllers\Api\AiQcmController::class, 'generateWithAI'])->name('qcm.generate_ai');
        Route::get('/resultats', [\App\Http\Controllers\Web\FormateurController::class, 'resultatsCohorte'])->name('resultats');
        Route::get('/resultats/export', [\App\Http\Controllers\Web\FormateurController::class, 'exportResultats'])->name('resultats.export');
        Route::get('/resultats/tentative/{id}/export', [\App\Http\Controllers\Web\FormateurController::class, 'exportTentative'])->name('resultats.tentative.export');
        Route::get('/etudiants/{id}/progression', [\App\Http\Controllers\Web\FormateurController::class, 'studentProgress'])->name('etudiants.progression');
        
        Route::get('/pedagogie', [\App\Http\Controllers\Web\FormateurController::class, 'pedagogie'])->name('pedagogie');
        Route::post('/pedagogie/seance', [\App\Http\Controllers\Web\FormateurController::class, 'storeSeance'])->name('pedagogie.seance.store');
        Route::get('/pedagogie/seance/{id}/edit', [\App\Http\Controllers\Web\FormateurController::class, 'editSeance'])->name('pedagogie.seance.edit');
        Route::put('/pedagogie/seance/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'updateSeance'])->name('pedagogie.seance.update');
        Route::delete('/pedagogie/seance/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroySeance'])->name('pedagogie.seance.destroy');
        Route::post('/pedagogie/seance/{id}/ua', [\App\Http\Controllers\Web\FormateurController::class, 'storeUA'])->name('pedagogie.ua.store');
        Route::get('/pedagogie/ua/{id}/edit', [\App\Http\Controllers\Web\FormateurController::class, 'editUA'])->name('pedagogie.ua.edit');
        Route::put('/pedagogie/ua/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'updateUA'])->name('pedagogie.ua.update');
        Route::delete('/pedagogie/ua/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroyUA'])->name('pedagogie.ua.destroy');
        Route::post('/pedagogie/ua/{id}/competence', [\App\Http\Controllers\Web\FormateurController::class, 'storeCompetence'])->name('pedagogie.competence.store');
        Route::get('/pedagogie/competence/{id}/edit', [\App\Http\Controllers\Web\FormateurController::class, 'editCompetence'])->name('pedagogie.competence.edit');
        Route::put('/pedagogie/competence/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'updateCompetence'])->name('pedagogie.competence.update');
        Route::delete('/pedagogie/competence/{id}', [\App\Http\Controllers\Web\FormateurController::class, 'destroyCompetence'])->name('pedagogie.competence.destroy');
    });

    // Group: Student Only
    Route::middleware(['role:etudiant'])->prefix('etudiant')->name('etudiant.')->group(function() {
        Route::get('/dashboard', [\App\Http\Controllers\Web\EtudiantController::class, 'dashboard'])->name('dashboard');
        Route::get('/progression', [\App\Http\Controllers\Web\EtudiantController::class, 'progression'])->name('progression');
        Route::get('/bibliotheque', [\App\Http\Controllers\Web\EtudiantController::class, 'bibliotheque'])->name('bibliotheque');
        Route::get('/bibliotheque/search', [\App\Http\Controllers\Web\EtudiantController::class, 'bibliothequeSearch'])->name('bibliotheque.search');
        Route::get('/qcm/{id}', [\App\Http\Controllers\Web\EtudiantController::class, 'passation'])->name('passation');
        Route::post('/qcm/{id}', [\App\Http\Controllers\Web\EtudiantController::class, 'submitQcm'])->name('qcm.submit');
        Route::post('/qcm/{id}/save', [\App\Http\Controllers\Web\EtudiantController::class, 'saveProgress'])->name('qcm.save');
        Route::get('/qcm/{id}/resultats', [\App\Http\Controllers\Web\EtudiantController::class, 'resultats'])->name('resultats');
        Route::get('/qcm/{id}/export', [\App\Http\Controllers\Web\EtudiantController::class, 'exportResultat'])->name('resultats.export');
        Route::post('/ai/explain-question', [\App\Http\Controllers\Api\AiExplainController::class, 'explainQuestion'])->name('ai.explain');
    });

    // Shared Profile Routes
    Route::get('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Web\ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Auth::routes(['register' => false]);
