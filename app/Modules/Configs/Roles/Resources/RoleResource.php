<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transforms the role into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
