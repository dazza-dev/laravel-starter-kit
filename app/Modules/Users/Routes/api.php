<?php

declare(strict_types=1);

use App\Modules\Users\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    Route::post('users/{uuid}/restore', [UserController::class, 'restore'])->middleware('permission:update-users');

    Route::get('users', [UserController::class, 'index'])->middleware('permission:read-users');
    Route::get('users/{uuid}', [UserController::class, 'show'])->middleware('permission:read-users');
    Route::post('users', [UserController::class, 'store'])->middleware('permission:create-users');
    Route::put('users/{uuid}', [UserController::class, 'update'])->middleware('permission:update-users');
    Route::delete('users/{uuid}', [UserController::class, 'destroy'])->middleware('permission:delete-users');
});
