<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transforms the Group model into an array for API responses, without the internal id: clients use uuid.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
