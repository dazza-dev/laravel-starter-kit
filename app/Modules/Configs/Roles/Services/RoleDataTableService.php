<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles\Services;

use App\Modules\Configs\Roles\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleDataTableService
{
    /**
     * Fields allowed for sorting, from the API name to the real column;
     * anything else is ignored to avoid opening the door to SQL injection.
     */
    private const SORTABLE = [
        'displayName' => 'display_name',
        'name' => 'name',
        'description' => 'description',
        'createdAt' => 'created_at',
    ];

    /**
     * Paginated roles with optional search and sort; pass $onlyTrashed=true for the trash view.
     */
    public function dataTable(?string $search, int $perPage, bool $onlyTrashed = false, array $sortBy = []): LengthAwarePaginator
    {
        $sort = dataTableSort($sortBy, self::SORTABLE, 'displayName');

        return Role::query()
            ->when($onlyTrashed, fn ($q) => $q->onlyTrashed())
            ->when($search, fn ($q) => $q->where('display_name', 'like', "%{$search}%"))
            ->orderBy($sort['column'], $sort['order'])
            ->paginate($perPage);
    }
}
