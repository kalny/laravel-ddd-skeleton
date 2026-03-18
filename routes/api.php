<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])
            ->name('register');
    });
    Route::prefix('users')->name('users.')->group(function () {
        Route::post('/{id}/change-name', [UserController::class, 'changeName'])
            ->name('change-name');
        Route::post('/{id}/change-password', [UserController::class, 'changePassword'])
            ->name('change-password');
    });
});
