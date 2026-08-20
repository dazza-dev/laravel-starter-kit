<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class GroupsServiceProvider extends ServiceProvider
{
    /**
     * Inicializa el módulo Groups: registra traducciones y rutas API.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Configs/Groups', 'Lang'), 'groups');
        $this->mapApiRoutes();
    }

    /**
     * Registra las rutas API del módulo bajo el prefijo api/v1.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Configs/Groups', 'Routes/api.php'));
    }
}
