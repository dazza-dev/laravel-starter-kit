<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Configs\Roles\Requests\RoleFilterRequest;
use App\Modules\Configs\Roles\Requests\RoleRequest;
use App\Modules\Configs\Roles\Resources\RoleResource;
use App\Modules\Configs\Roles\Services\RoleDataTableService;
use App\Modules\Configs\Roles\Services\RoleService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function __construct(
        private RoleService $roleService,
        private RoleDataTableService $roleDataTableService,
    ) {}

    /**
     * Paginated list of roles; pass only_trashed=1 for the trash view.
     */
    public function index(RoleFilterRequest $request): AnonymousResourceCollection
    {
        $roles = $this->roleDataTableService->dataTable(
            $request->validated('search'),
            (int) $request->validated('per_page', 15),
            $request->boolean('only_trashed'),
            $request->validated('sort_by') ?? [],
        );

        return RoleResource::collection($roles);
    }

    /**
     * Gets a single role by UUID.
     */
    public function show(string $uuid): Response
    {
        $role = $this->roleService->findByUuidOrFail($uuid);

        return response([
            'data' => RoleResource::make($role),
        ]);
    }

    /**
     * Creates a new role.
     */
    public function store(RoleRequest $request): Response
    {
        $role = $this->roleService->create($request->validated());

        return response([
            'data' => RoleResource::make($role),
            'message' => __('roles::messages.created'),
        ], 201);
    }

    /**
     * Updates an existing role.
     */
    public function update(RoleRequest $request, string $uuid): Response
    {
        $role = $this->roleService->findByUuidOrFail($uuid);
        $role = $this->roleService->update($role, $request->validated());

        return response([
            'data' => RoleResource::make($role),
            'message' => __('roles::messages.updated'),
        ]);
    }

    /**
     * Deletes a role.
     */
    public function destroy(string $uuid): Response
    {
        $role = $this->roleService->findByUuidOrFail($uuid);
        $this->roleService->delete($role);

        return response([
            'message' => __('roles::messages.deleted'),
        ]);
    }

    /**
     * Restores a deleted role.
     */
    public function restore(string $uuid): Response
    {
        $role = $this->roleService->findTrashedByUuidOrFail($uuid);
        $this->roleService->restore($role);

        return response([
            'message' => __('roles::messages.restored'),
        ]);
    }
}
