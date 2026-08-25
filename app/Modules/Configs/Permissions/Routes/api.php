<?php

declare(strict_types=1);

use App\Modules\Configs\Permissions\Controllers\MyPermissionsController;
use App\Modules\Configs\Permissions\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    // The user's own permissions: no gate, everyone can read their own.
    Route::get('permissions/me', MyPermissionsController::class);

    // A role's permission matrix is part of role management, hence `update-roles`.
    Route::middleware('permission:update-roles')->group(function () {
        Route::get('roles/{uuid}/permissions', [RolePermissionController::class, 'show']);
        Route::put('roles/{uuid}/permissions', [RolePermissionController::class, 'update']);
    });
});
