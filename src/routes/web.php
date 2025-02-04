<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SetUsernameController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::middleware('guest')->group(function () {
    Route::get('/welcome', function () {
        return view('welcome');
    })->name('welcome');
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Registration routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/set-username', [SetUsernameController::class, 'show'])->name('set-username');
    Route::post('/set-username', [SetUsernameController::class, 'store']);
    Route::get('/username-suggestions', [SetUsernameController::class, 'generateSuggestions']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
