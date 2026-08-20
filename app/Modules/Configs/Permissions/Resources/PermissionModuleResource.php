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
     * Un módulo con sus grupos: el primer nivel de la matriz del frontend.
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
