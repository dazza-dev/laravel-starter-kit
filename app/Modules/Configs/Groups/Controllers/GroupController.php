<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Configs\Groups\Requests\GroupFilterRequest;
use App\Modules\Configs\Groups\Requests\GroupRequest;
use App\Modules\Configs\Groups\Resources\GroupResource;
use App\Modules\Configs\Groups\Services\GroupDataTableService;
use App\Modules\Configs\Groups\Services\GroupService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class GroupController extends Controller
{
    public function __construct(
        private GroupService $groupService,
        private GroupDataTableService $groupDataTableService,
    ) {}

    /**
     * Listado paginado de grupos; pasar only_trashed=1 para la vista de papelera.
     */
    public function index(GroupFilterRequest $request): AnonymousResourceCollection
    {
        $groups = $this->groupDataTableService->dataTable(
            $request->validated('search'),
            (int) $request->validated('per_page', 15),
            $request->boolean('only_trashed'),
        );

        return GroupResource::collection($groups);
    }

    /**
     * Obtiene un grupo individual por UUID.
     */
    public function show(string $uuid): Response
    {
        $group = $this->groupService->findByUuidOrFail($uuid);

        return response([
            'data' => GroupResource::make($group),
        ]);
    }

    /**
     * Crea un nuevo grupo.
     */
    public function store(GroupRequest $request): Response
    {
        $group = $this->groupService->create($request->validated());

        return response([
            'data' => GroupResource::make($group),
            'message' => __('groups::messages.created'),
        ], 201);
    }

    /**
     * Actualiza un grupo existente.
     */
    public function update(GroupRequest $request, string $uuid): Response
    {
        $group = $this->groupService->findByUuidOrFail($uuid);
        $group = $this->groupService->update($group, $request->validated());

        return response([
            'data' => GroupResource::make($group),
            'message' => __('groups::messages.updated'),
        ]);
    }

    /**
     * Elimina un grupo.
     */
    public function destroy(string $uuid): Response
    {
        $group = $this->groupService->findByUuidOrFail($uuid);
        $this->groupService->delete($group);

        return response([
            'message' => __('groups::messages.deleted'),
        ]);
    }

    /**
     * Restaura un grupo eliminado.
     */
    public function restore(string $uuid): Response
    {
        $group = $this->groupService->findTrashedByUuidOrFail($uuid);
        $this->groupService->restore($group);

        return response([
            'message' => __('groups::messages.restored'),
        ]);
    }
}
