<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Resources;

use App\Modules\Configs\Permissions\Support\TranslatesNames;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionModuleResource extends JsonResource
{
    use TranslatesNames;

    /**
     * A module with its groups: the first level of the frontend matrix.
     */
    public function toArray(Request $request): array
    {
        $module = $this['module'];

        return [
            'module' => $module,
            'label' => $this->translateName('modules', $module ?? 'general'),
            'icon' => $this['icon'],
            'groups' => PermissionGroupResource::collection($this['groups']),
        ];
    }
}
