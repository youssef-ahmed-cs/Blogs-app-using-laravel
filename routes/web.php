<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FollowController;

Route::get('/', static function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'loginForm')->name('login');
    Route::post('login', 'loginPost')->name('login.post');
    Route::get('register', 'registerForm')->name('register');
    Route::post('register', 'registerPost')->name('register.post');
    Route::post('logout', 'logout')->name('logout');
});

Route::middleware('auth')->group(function () {
    // Posts routes
    Route::resource('posts', PostController::class);
    
    // Like routes - using LikeController only
    Route::post('/posts/{post}/toggle-like', [LikeController::class, 'toggleLike'])->name('posts.toggle-like');
    
    // Comments routes
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{id}', [ProfileController::class, 'public'])->name('profile.public');
    
    // Notifications routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'show'])->name('settings.show');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');
    Route::delete('/settings/delete', [SettingsController::class, 'destroy'])->name('settings.destroy');
    
    // Dashboard route
    Route::get('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');
});
Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.markAsRead')
    ->middleware('auth');


Route::post('/posts/{post}/repost', [PostController::class, 'repost'])->name('posts.repost');


Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');

Route::post('/profile/{user}/cover-upload', [ProfileController::class, 'uploadCover'])->name('profile.cover.upload');