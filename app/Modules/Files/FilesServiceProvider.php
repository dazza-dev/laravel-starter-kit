<?php

declare(strict_types=1);

namespace App\Modules\Files;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FilesServiceProvider extends ServiceProvider
{
    /**
     * Boots the Files module: registers translations and API routes.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Files', 'Lang'), 'files');
        $this->mapApiRoutes();
    }

    /**
     * Registers the module's API routes under the api/v1 prefix.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Files', 'Routes/api.php'));
    }
}
