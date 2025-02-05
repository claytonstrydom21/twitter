<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SetUsernameController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/set-username', [SetUsernameController::class, 'show'])->name('set-username');
    Route::post('/set-username', [SetUsernameController::class, 'store']);
    Route::get('/username-suggestions', [SetUsernameController::class, 'generateSuggestions']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
