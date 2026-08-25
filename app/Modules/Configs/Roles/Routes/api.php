<?php

declare(strict_types=1);

use App\Modules\Configs\Roles\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    Route::post('roles/{uuid}/restore', [RoleController::class, 'restore'])->middleware('permission:update-roles');

    Route::get('roles', [RoleController::class, 'index'])->middleware('permission:read-roles');
    Route::get('roles/{uuid}', [RoleController::class, 'show'])->middleware('permission:read-roles');
    Route::post('roles', [RoleController::class, 'store'])->middleware('permission:create-roles');
    Route::put('roles/{uuid}', [RoleController::class, 'update'])->middleware('permission:update-roles');
    Route::delete('roles/{uuid}', [RoleController::class, 'destroy'])->middleware('permission:delete-roles');
});
