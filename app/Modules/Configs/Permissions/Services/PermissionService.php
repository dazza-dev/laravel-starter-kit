<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Services;

use App\Modules\Configs\Permissions\Models\Permission;
use App\Modules\Configs\Roles\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Resolves the permissions catalog and its assignments.
 */
class PermissionService
{
    /**
     * Full catalog with its module loaded.
     */
    public function available(): Collection
    {
        return Permission::with('module')
            ->orderBy('group')
            ->orderBy('order')
            ->orderBy('id')
            ->get();
    }

    /**
     * Returns the catalog in two levels — module and group — with moduleless permissions in a separate trailing block.
     *
     * @return array<int, array{module: ?string, icon: ?string, groups: array}>
     */
    public function tree(): array
    {
        return $this->available()
            ->groupBy(fn (Permission $permission) => $permission->module?->name ?? '')
            ->sortBy(fn (Collection $permissions, string $module) => $module === ''
                ? PHP_INT_MAX
                : $permissions->first()->module->order)
            ->map(fn (Collection $permissions, string $module) => [
                'module' => $module ?: null,
                'icon' => $permissions->first()->module?->icon,
                'groups' => $permissions
                    ->groupBy('group')
                    ->map(fn (Collection $grouped, string $group) => [
                        'group' => $group,
                        'permissions' => $grouped,
                    ])
                    ->values()
                    ->all(),
            ])
            ->values()
            ->all();
    }

    /**
     * IDs of the permissions assigned to a role.
     */
    public function assignedIds(Role $role): array
    {
        return DB::table('permission_role')
            ->where('role_id', $role->id)
            ->pluck('permission_id')
            ->all();
    }

    /**
     * UUIDs of the permissions assigned to a role, which is what the frontend consumes.
     */
    public function assignedUuids(Role $role): array
    {
        return Permission::query()
            ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
            ->where('permission_role.role_id', $role->id)
            ->pluck('permissions.uuid')
            ->all();
    }

    /**
     * Replaces a role's permissions with the received set.
     *
     * @param  array<int, string>  $uuids
     */
    public function syncRolePermissions(Role $role, array $uuids): void
    {
        $ids = Permission::whereIn('uuid', $uuids)->pluck('id')->all();

        DB::transaction(function () use ($role, $ids) {
            DB::table('permission_role')->where('role_id', $role->id)->delete();

            if (! $ids) {
                return;
            }

            DB::table('permission_role')->insert(
                array_map(fn (int $id) => ['role_id' => $role->id, 'permission_id' => $id], $ids),
            );
        });
    }

    /**
     * Names of a user's effective permissions: those from their roles plus the ones assigned directly.
     *
     * @param  array<int, int>  $roleIds
     * @return array<int, string>
     */
    public function namesForUser(int $userId, array $roleIds): array
    {
        return Permission::query()
            ->where(fn ($query) => $query
                ->whereExists(fn ($sub) => $sub->selectRaw(1)->from('permission_user')
                    ->whereColumn('permission_user.permission_id', 'permissions.id')
                    ->where('permission_user.user_id', $userId))
                ->when($roleIds, fn ($query) => $query
                    ->orWhereExists(fn ($sub) => $sub->selectRaw(1)->from('permission_role')
                        ->whereColumn('permission_role.permission_id', 'permissions.id')
                        ->whereIn('permission_role.role_id', $roleIds))))
            ->pluck('name')
            ->all();
    }
}
