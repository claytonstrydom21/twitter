<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SetUsernameController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    //Landing page
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    //registration
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    //login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    //after registration set-username
    Route::get('/set-username', [SetUsernameController::class, 'show'])->name('set-username');
    Route::post('/set-username', [SetUsernameController::class, 'store']);
    Route::get('/username-suggestions', [SetUsernameController::class, 'generateSuggestions']);

    //home page after authentication (login/register)
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    //profile view
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{user:username}', [ProfileController::class, 'show'])->name('profile.show');

    //follow/unfollow
    Route::post('/users/{user}/follow', [FollowerController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/follow', [FollowerController::class, 'unfollow'])->name('users.unfollow');

    //explore page
    Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
    Route::get('/posts/discover', [PostController::class, 'getUnfollowedPosts']);

    //search
    Route::get('/search/users', [SearchController::class, 'users']);

    //show posts in feed
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    //like/unlike
    Route::post('/posts/{post}/like', [LikeController::class, 'like']);
    Route::delete('/posts/{post}/like', [LikeController::class, 'unlike']);

    //comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    //notifications
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-read', [NotificationsController::class, 'markAsRead']);
    Route::get('/notifications/count', [NotificationsController::class, 'getUnreadCount']);
    Route::post('/notifications/mark-read', [NotificationsController::class, 'markAllRead']);

    //logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
