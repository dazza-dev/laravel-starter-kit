<?php

declare(strict_types=1);

use App\Modules\Configs\Settings\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('settings', [SettingsController::class, 'index']);

Route::middleware('auth:web')->group(function () {
    Route::put('settings', [SettingsController::class, 'update'])->middleware('permission:update-config');
    Route::post('settings/upload-logo', [SettingsController::class, 'uploadLogo'])->middleware('permission:update-config');

    // Listas para los selects. Sin permiso propio: hacen falta para pintar cualquier formulario.
    Route::get('settings/roles', [SettingsController::class, 'roles']);
    Route::get('settings/groups', [SettingsController::class, 'groups']);
});
