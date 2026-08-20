<?php

declare(strict_types=1);

namespace App\Modules\Auth;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Inicializa el módulo Auth: registra traducciones y rutas API.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Auth', 'Lang'), 'auth');
        $this->mapApiRoutes();
    }

    /**
     * Registra las rutas API del módulo bajo el prefijo api/v1.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Auth', 'Routes/api.php'));
    }
}
