<?php

declare(strict_types=1);

namespace App\Modules\Users\Services;

use App\Modules\Configs\Roles\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Busca un usuario por UUID, con sus roles cargados, o null si no se encuentra.
     */
    public function findByUuid(string $uuid): ?User
    {
        return User::with('roles')->where('uuid', $uuid)->first();
    }

    /**
     * Busca un usuario por UUID o aborta con 404.
     */
    public function findByUuidOrFail(string $uuid): User
    {
        $user = $this->findByUuid($uuid);

        if (! $user) {
            abort(404, __('users::messages.not_found'));
        }

        return $user;
    }

    /**
     * Busca un usuario eliminado por UUID o aborta con 404.
     */
    public function findTrashedByUuidOrFail(string $uuid): User
    {
        $user = User::onlyTrashed()->where('uuid', $uuid)->first();

        if (! $user) {
            abort(404, __('users::messages.not_found'));
        }

        return $user;
    }

    /**
     * Crea un usuario y le asigna sus roles.
     */
    public function create(array $data, int $createdBy): User
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $roleUuids = $data['role_uuids'] ?? [];
            unset($data['role_uuids']);

            $data['created_by'] = $createdBy;
            $user = User::create($data);
            $user->roles()->sync($this->roleIdsFromUuids($roleUuids));

            return $user->load('roles');
        });
    }

    /**
     * Actualiza un usuario y sincroniza sus roles; una contraseña vacía significa "no cambiarla".
     */
    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $roleUuids = $data['role_uuids'] ?? null;
            unset($data['role_uuids']);

            if (blank($data['password'] ?? null)) {
                unset($data['password']);
            }

            $user->update($data);

            if ($roleUuids !== null) {
                $user->roles()->sync($this->roleIdsFromUuids($roleUuids));
            }

            return $user->fresh('roles');
        });
    }

    /**
     * Elimina (soft-delete) un usuario.
     */
    public function delete(User $user): void
    {
        $user->delete();
    }

    /**
     * Restaura un usuario eliminado.
     */
    public function restore(User $user): void
    {
        $user->restore();
    }

    /**
     * Impide que un usuario se elimine o se desactive a sí mismo.
     */
    public function guardSelf(User $user, int $authenticatedId): void
    {
        if ($user->id === $authenticatedId) {
            abort(403, __('users::messages.self_action'));
        }
    }

    /**
     * Traduce los UUID de rol que envía el frontend a claves primarias.
     *
     * @param  array<int, string>  $uuids
     * @return array<int, int>
     */
    private function roleIdsFromUuids(array $uuids): array
    {
        if (! $uuids) {
            return [];
        }

        return Role::whereIn('uuid', $uuids)->pluck('id')->all();
    }
}
