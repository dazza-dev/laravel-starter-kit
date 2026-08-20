<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Configs\Permissions\Requests\SyncRolePermissionsRequest;
use App\Modules\Configs\Permissions\Resources\PermissionModuleResource;
use App\Modules\Configs\Permissions\Services\PermissionService;
use App\Modules\Configs\Roles\Services\RoleService;
use Illuminate\Http\JsonResponse;

class RolePermissionController extends Controller
{
    public function __construct(
        private PermissionService $permissionService,
        private RoleService $roleService,
    ) {}

    /**
     * Devuelve el catálogo de permisos y cuáles tiene asignados el rol.
     */
    public function show(string $uuid): JsonResponse
    {
        $role = $this->roleService->findByUuidOrFail($uuid);

        return response()->json([
            'data' => PermissionModuleResource::collection($this->permissionService->tree()),
            'assigned' => $this->permissionService->assignedUuids($role),
        ]);
    }

    /**
     * Reemplaza los permisos del rol por los recibidos.
     */
    public function update(SyncRolePermissionsRequest $request, string $uuid): JsonResponse
    {
        $role = $this->roleService->findByUuidOrFail($uuid);

        $this->permissionService->syncRolePermissions($role, $request->validated('permissions', []));

        return response()->json([
            'assigned' => $this->permissionService->assignedUuids($role),
            'message' => __('permissions::messages.updated'),
        ]);
    }
}
