<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncRolePermissionsRequest extends FormRequest
{
    /**
     * La ruta ya exige el permiso `update-roles`, así que aquí no hay nada más que autorizar.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para sincronizar los permisos de un rol.
     */
    public function rules(): array
    {
        return [
            // Un array vacío es válido: significa dejar el rol sin permisos.
            'permissions' => ['present', 'array'],
            'permissions.*' => ['required', 'string', 'uuid'],
        ];
    }

    /**
     * Mensajes de validación.
     */
    public function messages(): array
    {
        return [
            'permissions.present' => __('permissions::validation.permissions.present'),
            'permissions.array' => __('permissions::validation.permissions.array'),
            'permissions.*.uuid' => __('permissions::validation.permissions.uuid'),
        ];
    }
}
