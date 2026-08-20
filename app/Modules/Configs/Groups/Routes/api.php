<?php

declare(strict_types=1);

use App\Modules\Configs\Groups\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    Route::post('groups/{uuid}/restore', [GroupController::class, 'restore'])->middleware('permission:update-groups');

    Route::get('groups', [GroupController::class, 'index'])->middleware('permission:read-groups');
    Route::get('groups/{uuid}', [GroupController::class, 'show'])->middleware('permission:read-groups');
    Route::post('groups', [GroupController::class, 'store'])->middleware('permission:create-groups');
    Route::put('groups/{uuid}', [GroupController::class, 'update'])->middleware('permission:update-groups');
    Route::delete('groups/{uuid}', [GroupController::class, 'destroy'])->middleware('permission:delete-groups');
});
