<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MyPermissionsController extends Controller
{
    /**
     * Devuelve los permisos del usuario autenticado, que el frontend usa para CASL, el menú y las rutas.
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
