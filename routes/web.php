<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentNotificationController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

Route::get('/', static function () {
    return view('welcome');
});

// Authentication Routes

Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerPost')->name('post.register');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginPost')->name('login.post');
});

Route::controller(AuthController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('logout', 'logout')->name('logout');
    Route::get('dashboard', 'dashboard')->name('dashboard');
});


Route::prefix('posts')->middleware('auth:sanctum')->group(function () {
    Route::controller(PostController::class)->group(function () {
        Route::get('/', 'index')->name('posts.index');
        Route::get('/create', 'create')->name('posts.create');
        Route::post('/', 'store')->name('posts.store');
        Route::get('/{post}', 'show')->name('posts.show');
        Route::get('/{post}/edit', 'edit')->name('posts.edit');
        Route::put('/{post}', 'update')->name('posts.update');
        Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])
            ->name('posts.toggleLike');

        Route::post('{post}/comments', [PostController::class, 'storeComment'])->name('posts.comments.store');
        Route::delete('/posts/comments/{comment}', [CommentController::class, 'destroy'])
            ->name('comments.destroy');

        Route::delete('/{post}', 'destroy')->name('posts.destroy')
            ->withoutMiddleware([VerifyCsrfToken::class]);
    });
});

Route::get('/notifications', [CommentNotificationController::class, 'index'])
    ->name('notifications.index');



