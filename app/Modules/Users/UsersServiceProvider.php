<?php

declare(strict_types=1);

namespace App\Modules\Users;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * Boots the Users module: registers translations and API routes.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Users', 'Lang'), 'users');
        $this->mapApiRoutes();
    }

    /**
     * Registers the module's API routes under the api/v1 prefix.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Users', 'Routes/api.php'));
    }
}
