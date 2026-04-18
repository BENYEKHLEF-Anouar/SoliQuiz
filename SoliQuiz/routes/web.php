<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('public.landing');
})->name('home');

Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');