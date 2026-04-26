<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::prefix('student')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
    Route::get('/qcm/{id}', function ($id) {
        return view('student.qcm-passation', ['qcmId' => $id]);
    })->name('student.qcm');
    Route::get('/qcm/{id}/result', function ($id) {
        return view('student.qcm-result', ['qcmId' => $id]);
    })->name('student.qcm-result');
    Route::get('/history', function () {
        return view('student.history');
    })->name('student.history');
    Route::get('/profile', function () {
        return view('student.profile');
    })->name('student.profile');
});

Route::prefix('formateur')->group(function () {
    Route::get('/qcms', function () {
        return view('formateur.qcms');
    })->name('formateur.qcms');
    Route::get('/qcm/{id}/results', function ($id) {
        return view('formateur.qcm-results', ['qcmId' => $id]);
    })->name('formateur.qcm-results');
    Route::get('/class-notes', function () {
        return view('formateur.class-notes');
    })->name('formateur.class-notes');
    Route::get('/profile', function () {
        return view('formateur.profile');
    })->name('formateur.profile');
});