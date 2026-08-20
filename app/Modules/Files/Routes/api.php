<?php

declare(strict_types=1);

use App\Modules\Files\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    Route::get('files/{folder}/{filename}', [FileController::class, 'show']);
});
