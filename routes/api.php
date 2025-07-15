<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [UserController::class, 'register'])->name('api.register');
Route::post('login', [UserController::class, 'login'])->name('api.login');
Route::post('logout', [UserController::class, 'logout'])->name('api.logout')
    ->middleware('auth:sanctum');

// Route::post('/register', [PostController::class, 'register'])
//     ->name('api.register');
