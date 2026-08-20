<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles\Services;

use App\Modules\Configs\Roles\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleService
{
    /**
     * Busca un rol por UUID, o null si no se encuentra.
     */
    public function findByUuid(string $uuid): ?Role
    {
        return Role::where('uuid', $uuid)->first();
    }

    /**
     * Busca un rol por UUID o aborta con 404.
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
     * Busca un rol eliminado por UUID o aborta con 404.
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
     * Crea un rol cuyo slug sale de display_name.
     */
    public function create(array $data): Role
    {
        $data['name'] = Str::slug($data['display_name']);

        return Role::create($data);
    }

    /**
     * Actualiza un rol; el slug de los roles del sistema es intocable.
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
     * Elimina (soft-delete) un rol junto con sus asignaciones de permisos.
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
     * Restaura un rol eliminado.
     */
    public function restore(Role $role): void
    {
        $role->restore();
    }

    /**
     * Impide operar sobre un rol del sistema.
     */
    private function guardSystemRole(Role $role): void
    {
        if ($role->isSystemRole()) {
            abort(403, __('roles::messages.system_role'));
        }
    }
}
