<?php

declare(strict_types=1);

namespace App\Modules\Files;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FilesServiceProvider extends ServiceProvider
{
    /**
     * Inicializa el módulo Files: registra traducciones y rutas API.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Files', 'Lang'), 'files');
        $this->mapApiRoutes();
    }

    /**
     * Registra las rutas API del módulo bajo el prefijo api/v1.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Files', 'Routes/api.php'));
    }
}
