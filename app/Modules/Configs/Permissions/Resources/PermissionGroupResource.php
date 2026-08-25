<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Resources;

use App\Modules\Configs\Permissions\Support\TranslatesNames;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionGroupResource extends JsonResource
{
    use TranslatesNames;

    /**
     * A catalog group with its permissions: each row of the frontend matrix.
     */
    public function toArray(Request $request): array
    {
        return [
            'group' => $this['group'],
            'label' => $this->translateName('groups', $this['group']),
            'permissions' => PermissionResource::collection($this['permissions']),
        ];
    }
}
