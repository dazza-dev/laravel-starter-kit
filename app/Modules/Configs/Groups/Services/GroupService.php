<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups\Services;

use App\Modules\Configs\Groups\Models\Group;

class GroupService
{
    /**
     * Finds a group by UUID, or null if not found.
     */
    public function findByUuid(string $uuid): ?Group
    {
        return Group::where('uuid', $uuid)->first();
    }

    /**
     * Finds a group by UUID or aborts with 404.
     */
    public function findByUuidOrFail(string $uuid): Group
    {
        $group = $this->findByUuid($uuid);

        if (! $group) {
            abort(404, __('groups::messages.not_found'));
        }

        return $group;
    }

    /**
     * Finds a deleted group by UUID or aborts with 404.
     */
    public function findTrashedByUuidOrFail(string $uuid): Group
    {
        $group = Group::onlyTrashed()->where('uuid', $uuid)->first();

        if (! $group) {
            abort(404, __('groups::messages.not_found'));
        }

        return $group;
    }

    /**
     * Creates a new group from the validated data.
     */
    public function create(array $data): Group
    {
        return Group::create($data);
    }

    /**
     * Updates an existing group with the validated data.
     */
    public function update(Group $group, array $data): Group
    {
        $group->update($data);

        return $group;
    }

    /**
     * Soft-deletes a group.
     */
    public function delete(Group $group): void
    {
        $group->delete();
    }

    /**
     * Restores a deleted group.
     */
    public function restore(Group $group): void
    {
        $group->restore();
    }
}
