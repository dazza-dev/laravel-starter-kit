<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transforma el modelo Group en un arreglo para las respuestas API, sin el id interno: los clientes usan uuid.
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
