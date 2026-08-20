<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups\Services;

use App\Modules\Configs\Groups\Models\Group;
use Illuminate\Pagination\LengthAwarePaginator;

class GroupDataTableService
{
    /**
     * Grupos paginados con búsqueda opcional; pasa $onlyTrashed=true para la vista de papelera.
     */
    public function dataTable(?string $search, int $perPage, bool $onlyTrashed = false): LengthAwarePaginator
    {
        return Group::query()
            ->when($onlyTrashed, fn ($q) => $q->onlyTrashed())
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate($perPage);
    }
}
