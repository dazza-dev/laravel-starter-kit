<?php

declare(strict_types=1);

namespace App\Modules\Users;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * Inicializa el módulo Users: registra traducciones y rutas API.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Users', 'Lang'), 'users');
        $this->mapApiRoutes();
    }

    /**
     * Registra las rutas API del módulo bajo el prefijo api/v1.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Users', 'Routes/api.php'));
    }
}
