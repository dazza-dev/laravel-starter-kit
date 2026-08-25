<?php

declare(strict_types=1);

namespace App\Modules\Auth\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Exposes the uuid, the profile and the effective permissions, never the primary key.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->fullName(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'username' => $this->username,
            'avatar' => $this->avatar,
            'roles' => $this->whenLoaded('roles', fn () => $this->roles->map(fn ($role) => [
                'uuid' => $role->uuid,
                'name' => $role->display_name,
                'slug' => $role->name,
            ])->values()),
            'is_admin' => $this->isAdmin(),
            'permissions' => $this->permissionNames(),
        ];
    }
}
