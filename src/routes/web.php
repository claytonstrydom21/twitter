<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SetUsernameController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
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


    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{user:username}', [ProfileController::class, 'show'])->name('profile.show');

    Route::post('/users/{user}/follow', [FollowerController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/follow', [FollowerController::class, 'unfollow'])->name('users.unfollow');

    Route::get('/explore', function () {
        return view('explore');
    })->name('explore');
    Route::get('/search/users', [SearchController::class, 'users']);
    Route::get('/posts/discover', [PostController::class, 'getUnfollowedPosts']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [LikeController::class, 'like']);
    Route::delete('/posts/{post}/like', [LikeController::class, 'unlike']);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
