<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider
{
    /**
     * Inicializa el módulo Roles: registra traducciones y rutas API.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Configs/Roles', 'Lang'), 'roles');
        $this->mapApiRoutes();
    }

    /**
     * Registra las rutas API del módulo bajo el prefijo api/v1.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Configs/Roles', 'Routes/api.php'));
    }
}
