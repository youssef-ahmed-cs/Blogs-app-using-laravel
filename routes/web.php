<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FollowController;

// Main posts route - both / and /posts should work
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index'); // Add this missing route

// Authentication routes (accessible to guests)
Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'loginForm')->name('login');
    Route::post('login', 'loginPost')->name('login.post');
    Route::get('register', 'registerForm')->name('register');
    Route::post('register', 'registerPost')->name('register.post');
    Route::post('logout', 'logout')->name('logout');
});

// Public routes (accessible to everyone)
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/profile/{id}', [ProfileController::class, 'public'])->name('profile.public');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Posts management
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // Like and Comment routes (require auth)
    Route::post('/posts/{post}/toggle-like', [LikeController::class, 'toggleLike'])->name('posts.toggle-like');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Follow/Unfollow routes
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
    Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/{user}/cover-upload', [ProfileController::class, 'uploadCover'])->name('profile.cover.upload');
    Route::post('/profile/{user}/avatar-upload', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
    
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Route::get('/settings', function() { 
    //     return redirect()->route('profile.show')->with('info', 'Use Profile Settings for now.'); 
    // })->name('settings.show');
    
    // Dashboard route
    Route::get('/dashboard', function() {
        return redirect()->route('home');
    })->name('dashboard');
});
Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingsController::class, 'show'])->name('settings.show');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');
    Route::delete('/settings/delete', [SettingsController::class, 'destroy'])->name('settings.destroy');
});