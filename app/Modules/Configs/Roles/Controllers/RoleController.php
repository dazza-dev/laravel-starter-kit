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
     * Listado paginado de roles; pasar only_trashed=1 para la vista de papelera.
     */
    public function index(RoleFilterRequest $request): AnonymousResourceCollection
    {
        $roles = $this->roleDataTableService->dataTable(
            $request->validated('search'),
            (int) $request->validated('per_page', 15),
            $request->boolean('only_trashed'),
        );

        return RoleResource::collection($roles);
    }

    /**
     * Obtiene un rol individual por UUID.
     */
    public function show(string $uuid): Response
    {
        $role = $this->roleService->findByUuidOrFail($uuid);

        return response([
            'data' => RoleResource::make($role),
        ]);
    }

    /**
     * Crea un nuevo rol.
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
     * Actualiza un rol existente.
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
     * Elimina un rol.
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
     * Restaura un rol eliminado.
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
