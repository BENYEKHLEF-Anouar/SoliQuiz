<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('public.landing');
})->name('home');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    if ($user->isFormateur()) return redirect()->route('formateur.dashboard');
    return redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
    Route::get('/formateur/dashboard', function () { return view('formateur.dashboard'); })->name('formateur.dashboard');
    Route::get('/student/dashboard', function () { return view('student.dashboard'); })->name('student.dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
