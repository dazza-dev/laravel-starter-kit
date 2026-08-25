<?php

declare(strict_types=1);

namespace App\Modules\Users\Services;

use App\Modules\Users\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserDataTableService
{
    /**
     * Fields allowed for sorting, from the API name to the real column;
     * anything else is ignored to avoid opening the door to SQL injection.
     */
    private const SORTABLE = [
        'firstName' => 'first_name',
        'lastName' => 'last_name',
        // The table shows the full name, sorted by first name.
        'fullName' => 'first_name',
        'email' => 'email',
        'username' => 'username',
        'status' => 'status',
        'lastLoginAt' => 'last_login_at',
        'createdAt' => 'created_at',
    ];

    /**
     * Paginated users with optional search, filters and sort; pass $filters['only_trashed'] = true for the trash view.
     */
    public function dataTable(?string $search, int $perPage, array $filters = [], array $sortBy = []): LengthAwarePaginator
    {
        $sort = dataTableSort($sortBy, self::SORTABLE, 'firstName');

        return User::query()
            ->with('roles')
            ->when($filters['only_trashed'] ?? false, fn ($q) => $q->onlyTrashed())
            ->when($search, fn ($q) => $q->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            }))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['roles'] ?? null, fn ($q, $roles) => $q->whereHas('roles', fn ($r) => $r->whereIn('uuid', $roles)))
            ->orderBy($sort['column'], $sort['order'])
            ->paginate($perPage);
    }
}
