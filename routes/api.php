<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])
            ->name('register');
        Route::post('/login', [AuthController::class, 'login'])
            ->name('login');
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout')
            ->middleware('auth:sanctum');
    });
    Route::prefix('users')->name('users.')->group(function () {
        Route::post('/{id}/change-email', [UserController::class, 'changeEmail'])
            ->name('change-email')
            ->middleware('auth:sanctum');
        Route::post('/{id}/change-password', [UserController::class, 'changePassword'])
            ->name('change-password')
            ->middleware('auth:sanctum');
    });
});
