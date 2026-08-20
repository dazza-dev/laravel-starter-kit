<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles\Services;

use App\Modules\Configs\Roles\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleDataTableService
{
    /**
     * Roles paginados con búsqueda opcional; pasa $onlyTrashed=true para la vista de papelera.
     */
    public function dataTable(?string $search, int $perPage, bool $onlyTrashed = false): LengthAwarePaginator
    {
        return Role::query()
            ->when($onlyTrashed, fn ($q) => $q->onlyTrashed())
            ->when($search, fn ($q) => $q->where('display_name', 'like', "%{$search}%"))
            ->orderBy('display_name')
            ->paginate($perPage);
    }
}
