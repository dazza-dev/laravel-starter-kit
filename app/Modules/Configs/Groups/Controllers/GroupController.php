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
     * Paginated list of groups; pass only_trashed=1 for the trash view.
     */
    public function index(GroupFilterRequest $request): AnonymousResourceCollection
    {
        $groups = $this->groupDataTableService->dataTable(
            $request->validated('search'),
            (int) $request->validated('per_page', 15),
            $request->boolean('only_trashed'),
            $request->validated('sort_by') ?? [],
        );

        return GroupResource::collection($groups);
    }

    /**
     * Gets a single group by UUID.
     */
    public function show(string $uuid): Response
    {
        $group = $this->groupService->findByUuidOrFail($uuid);

        return response([
            'data' => GroupResource::make($group),
        ]);
    }

    /**
     * Creates a new group.
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
     * Updates an existing group.
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
     * Deletes a group.
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
     * Restores a deleted group.
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
