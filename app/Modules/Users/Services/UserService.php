<?php

declare(strict_types=1);

namespace App\Modules\Users\Services;

use App\Modules\Configs\Roles\Models\Role;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Finds a user by UUID, with roles loaded, or null if not found.
     */
    public function findByUuid(string $uuid): ?User
    {
        return User::with('roles')->where('uuid', $uuid)->first();
    }

    /**
     * Finds a user by UUID or aborts with 404.
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
     * Finds a deleted user by UUID or aborts with 404.
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
     * Creates a user and assigns their roles.
     */
    public function create(array $data, int $createdBy): User
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $roleUuids = $data['role_uuids'] ?? [];
            unset($data['role_uuids']);

            $data['status'] ??= 'active';
            $data['created_by'] = $createdBy;
            $user = User::create($data);
            $user->roles()->sync($this->roleIdsFromUuids($roleUuids));

            return $user->load('roles');
        });
    }

    /**
     * Updates a user and syncs their roles; an empty password means "don't change it".
     */
    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $roleUuids = $data['role_uuids'] ?? null;
            unset($data['role_uuids']);

            if (blank($data['password'] ?? null)) {
                unset($data['password']);
            }

            // No status in the request keeps the current one.
            if (! isset($data['status'])) {
                unset($data['status']);
            }

            $user->update($data);

            if ($roleUuids !== null) {
                $user->roles()->sync($this->roleIdsFromUuids($roleUuids));
            }

            return $user->fresh('roles');
        });
    }

    /**
     * Soft-deletes a user.
     */
    public function delete(User $user): void
    {
        $user->delete();
    }

    /**
     * Restores a deleted user.
     */
    public function restore(User $user): void
    {
        $user->restore();
    }

    /**
     * Prevents a user from deleting or deactivating themselves.
     */
    public function guardSelf(User $user, int $authenticatedId): void
    {
        if ($user->id === $authenticatedId) {
            abort(403, __('users::messages.self_action'));
        }
    }

    /**
     * Translates the role UUIDs sent by the frontend into primary keys.
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
