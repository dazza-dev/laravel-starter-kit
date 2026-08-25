<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Resources;

use App\Modules\Configs\Permissions\Support\TranslatesNames;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    use TranslatesNames;

    /**
     * A single permission, with its label already translated to the user's language.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'label' => $this->translateName('actions', $this->name),
        ];
    }
}
