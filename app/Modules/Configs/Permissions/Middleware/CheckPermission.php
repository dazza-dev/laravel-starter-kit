<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Exige alguno de los permisos indicados (`permission:read-roles` o `permission:read-users|read-roles`) vía Gate, con bypass de administrador.
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        foreach (explode('|', $permissions) as $permission) {
            if ($user->can($permission)) {
                return $next($request);
            }
        }

        abort(403, __('permissions::messages.forbidden'));
    }
}
