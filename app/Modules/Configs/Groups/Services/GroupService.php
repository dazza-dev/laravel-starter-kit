<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups\Services;

use App\Modules\Configs\Groups\Models\Group;

class GroupService
{
    /**
     * Busca un grupo por UUID, o null si no se encuentra.
     */
    public function findByUuid(string $uuid): ?Group
    {
        return Group::where('uuid', $uuid)->first();
    }

    /**
     * Busca un grupo por UUID o aborta con 404.
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
     * Busca un grupo eliminado por UUID o aborta con 404.
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
     * Crea un nuevo grupo a partir de los datos validados.
     */
    public function create(array $data): Group
    {
        return Group::create($data);
    }

    /**
     * Actualiza un grupo existente con los datos validados.
     */
    public function update(Group $group, array $data): Group
    {
        $group->update($data);

        return $group;
    }

    /**
     * Elimina (soft-delete) un grupo.
     */
    public function delete(Group $group): void
    {
        $group->delete();
    }

    /**
     * Restaura un grupo eliminado.
     */
    public function restore(Group $group): void
    {
        $group->restore();
    }
}
