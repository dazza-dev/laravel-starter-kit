<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MyPermissionsController extends Controller
{
    /**
     * Returns the authenticated user's permissions, used by the frontend for CASL, the menu and routes.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'data' => [
                'permissions' => $user->permissionNames(),
                'is_admin' => $user->isAdmin(),
            ],
        ]);
    }
}
