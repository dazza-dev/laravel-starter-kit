<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class GroupsServiceProvider extends ServiceProvider
{
    /**
     * Boots the Groups module: registers translations and API routes.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Configs/Groups', 'Lang'), 'groups');
        $this->mapApiRoutes();
    }

    /**
     * Registers the module's API routes under the api/v1 prefix.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Configs/Groups', 'Routes/api.php'));
    }
}
