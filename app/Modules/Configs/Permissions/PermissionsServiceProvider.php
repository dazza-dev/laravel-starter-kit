<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions;

use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Boots the Permissions module: registers translations, API routes and the authorization Gate.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(module_path('Configs/Permissions', 'Lang'), 'permissions');
        $this->mapApiRoutes();
        $this->registerGate();
    }

    /**
     * Registers the module's API routes under the api/v1 prefix.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('Configs/Permissions', 'Routes/api.php'));
    }

    /**
     * Resolves any ability as a permission, with full bypass for admins; returns null instead of false so the Gate keeps evaluating.
     */
    protected function registerGate(): void
    {
        Gate::before(function (User $user, string $ability): ?bool {
            if ($user->isAdmin()) {
                return true;
            }

            return $user->hasPermission($ability) ? true : null;
        });
    }
}
