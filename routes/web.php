<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentNotificationController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

Route::get('/', static function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerPost')->name('post.register');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginPost')->name('login.post');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout')->name('logout');
        Route::get('dashboard', 'dashboard')->name('dashboard');
    });

    Route::prefix('posts')->controller(PostController::class)->group(function () {
        Route::get('/', 'index')->name('posts.index');
        Route::get('/create', 'create')->name('posts.create');
        Route::post('/', 'store')->name('posts.store');
        Route::get('/{post}', 'show')->name('posts.show');
        Route::get('/{post}/edit', 'edit')->name('posts.edit');
        Route::put('/{post}', 'update')->name('posts.update');
        Route::delete('/{post}', 'destroy')->name('posts.destroy');
    });

    Route::controller(CommentController::class)->group(function () {
        Route::post('posts/{post}/comments', 'store')->name('posts.comments.store');
        Route::delete('posts/comments/{comment}', 'destroy')->name('comments.destroy');
    });

    Route::controller(LikeController::class)->group(function () {
        Route::post('posts/{post}/like', 'toggleLike')->name('posts.toggleLike');
    });

    Route::get('/notifications', [CommentNotificationController::class, 'index'])
        ->name('notifications.index');

    Route::patch('/notifications/{id}/read', [CommentNotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::patch('/notifications/read-all', [CommentNotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');
});


