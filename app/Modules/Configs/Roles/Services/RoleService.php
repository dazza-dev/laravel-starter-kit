<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles\Services;

use App\Modules\Configs\Roles\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleService
{
    /**
     * Finds a role by UUID, or null if not found.
     */
    public function findByUuid(string $uuid): ?Role
    {
        return Role::where('uuid', $uuid)->first();
    }

    /**
     * Finds a role by UUID or aborts with 404.
     */
    public function findByUuidOrFail(string $uuid): Role
    {
        $role = $this->findByUuid($uuid);

        if (! $role) {
            abort(404, __('roles::messages.not_found'));
        }

        return $role;
    }

    /**
     * Finds a deleted role by UUID or aborts with 404.
     */
    public function findTrashedByUuidOrFail(string $uuid): Role
    {
        $role = Role::onlyTrashed()->where('uuid', $uuid)->first();

        if (! $role) {
            abort(404, __('roles::messages.not_found'));
        }

        return $role;
    }

    /**
     * Creates a role whose slug is derived from display_name.
     */
    public function create(array $data): Role
    {
        $data['name'] = Str::slug($data['display_name']);

        return Role::create($data);
    }

    /**
     * Updates a role; system roles' slugs are untouchable.
     */
    public function update(Role $role, array $data): Role
    {
        $data['name'] = $role->isSystemRole()
            ? $role->name
            : Str::slug($data['display_name']);

        $role->update($data);

        return $role;
    }

    /**
     * Soft-deletes a role along with its permission assignments.
     */
    public function delete(Role $role): void
    {
        $this->guardSystemRole($role);

        DB::transaction(function () use ($role) {
            DB::table('permission_role')->where('role_id', $role->id)->delete();
            DB::table('role_user')->where('role_id', $role->id)->delete();
            $role->delete();
        });
    }

    /**
     * Restores a deleted role.
     */
    public function restore(Role $role): void
    {
        $role->restore();
    }

    /**
     * Prevents operating on a system role.
     */
    private function guardSystemRole(Role $role): void
    {
        if ($role->isSystemRole()) {
            abort(403, __('roles::messages.system_role'));
        }
    }
}
