<?php

declare(strict_types=1);

namespace App\Modules\Configs\Settings;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Boots the Settings module: registers translations and API routes.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Configs/Settings', 'Lang'), 'settings');
        $this->mapApiRoutes();
    }

    /**
     * Registers the module's API routes under the api/v1 prefix.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Configs/Settings', 'Routes/api.php'));
    }
}
