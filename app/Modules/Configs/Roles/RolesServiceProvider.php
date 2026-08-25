<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider
{
    /**
     * Boots the Roles module: registers translations and API routes.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Configs/Roles', 'Lang'), 'roles');
        $this->mapApiRoutes();
    }

    /**
     * Registers the module's API routes under the api/v1 prefix.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Configs/Roles', 'Routes/api.php'));
    }
}
