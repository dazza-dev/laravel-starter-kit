<?php

declare(strict_types=1);

namespace App\Modules\Auth;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Boots the Auth module: registers translations and API routes.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Auth', 'Lang'), 'auth');
        $this->mapApiRoutes();
    }

    /**
     * Registers the module's API routes under the api/v1 prefix.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Auth', 'Routes/api.php'));
    }
}
