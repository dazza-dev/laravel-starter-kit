<?php

declare(strict_types=1);

namespace App\Modules\Users\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transforms the User model into an array for API responses, without the internal id: clients use uuid.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->fullName(),
            'email' => $this->email,
            'phone' => $this->phone,
            'username' => $this->username,
            'avatar' => $this->avatar,
            'status' => $this->status,
            'roles' => $this->whenLoaded('roles', fn () => $this->roles->map(fn ($role) => [
                'uuid' => $role->uuid,
                'name' => $role->display_name,
                'slug' => $role->name,
            ])->values()),
            'last_login_at' => $this->last_login_at,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
