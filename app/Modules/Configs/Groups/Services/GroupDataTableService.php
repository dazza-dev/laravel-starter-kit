<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups\Services;

use App\Modules\Configs\Groups\Models\Group;
use Illuminate\Pagination\LengthAwarePaginator;

class GroupDataTableService
{
    /**
     * Fields allowed for sorting, from the API name to the real column;
     * anything else is ignored to avoid opening the door to SQL injection.
     */
    private const SORTABLE = [
        'name' => 'name',
        'createdAt' => 'created_at',
    ];

    /**
     * Paginated groups with optional search and sort; pass $onlyTrashed=true for the trash view.
     */
    public function dataTable(?string $search, int $perPage, bool $onlyTrashed = false, array $sortBy = []): LengthAwarePaginator
    {
        $sort = dataTableSort($sortBy, self::SORTABLE, 'name');

        return Group::query()
            ->when($onlyTrashed, fn ($q) => $q->onlyTrashed())
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy($sort['column'], $sort['order'])
            ->paginate($perPage);
    }
}
