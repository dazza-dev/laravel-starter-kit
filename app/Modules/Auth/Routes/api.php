<?php

declare(strict_types=1);

use App\Modules\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:web')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Leer el propio perfil no lleva permiso: es la comprobación de sesión con la que arranca la SPA.
    Route::post('auth/profile', [AuthController::class, 'profile']);
    Route::put('auth/profile', [AuthController::class, 'updateProfile'])->middleware('permission:update-profile');
});
