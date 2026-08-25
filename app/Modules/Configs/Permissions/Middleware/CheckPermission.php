<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Requires any of the given permissions (`permission:read-roles` or `permission:read-users|read-roles`) via Gate, with admin bypass.
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
