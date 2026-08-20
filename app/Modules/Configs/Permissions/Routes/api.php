<?php

declare(strict_types=1);

use App\Modules\Configs\Permissions\Controllers\MyPermissionsController;
use App\Modules\Configs\Permissions\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    // Los permisos del propio usuario: no llevan gate, todo el mundo puede leer los suyos.
    Route::get('permissions/me', MyPermissionsController::class);

    // La matriz de permisos de un rol es parte de la gestión de roles, de ahí `update-roles`.
    Route::middleware('permission:update-roles')->group(function () {
        Route::get('roles/{uuid}/permissions', [RolePermissionController::class, 'show']);
        Route::put('roles/{uuid}/permissions', [RolePermissionController::class, 'update']);
    });
});
