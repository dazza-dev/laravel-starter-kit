<?php

declare(strict_types=1);

namespace App\Modules\Users\Services;

use App\Modules\Users\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserDataTableService
{
    /**
     * Columnas por las que se permite ordenar; cualquier otra se ignora para no abrir la puerta a inyección SQL.
     */
    private const SORTABLE = ['first_name', 'last_name', 'email', 'username', 'status', 'created_at'];

    /**
     * Usuarios paginados con búsqueda, filtros y orden opcionales; pasa $filters['only_trashed'] = true para la vista de papelera.
     */
    public function dataTable(?string $search, int $perPage, array $filters = [], array $sortBy = []): LengthAwarePaginator
    {
        $sort = $this->sortColumn($sortBy);

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
            ->orderBy($sort['key'], $sort['order'])
            ->paginate($perPage);
    }

    /**
     * Devuelve la columna de ordenación pedida por el cliente, o el orden por defecto
     * cuando no llega ninguna o la que llega no está permitida.
     */
    private function sortColumn(array $sortBy): array
    {
        $sort = $sortBy[0] ?? null;

        if (! $sort || ! in_array($sort['key'] ?? '', self::SORTABLE, true)) {
            return ['key' => 'first_name', 'order' => 'asc'];
        }

        return [
            'key' => $sort['key'],
            'order' => ($sort['order'] ?? 'asc') === 'desc' ? 'desc' : 'asc',
        ];
    }
}
