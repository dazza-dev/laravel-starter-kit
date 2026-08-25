<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncRolePermissionsRequest extends FormRequest
{
    /**
     * The route already requires the `update-roles` permission, so there's nothing else to authorize here.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for syncing a role's permissions.
     */
    public function rules(): array
    {
        return [
            // An empty array is valid: it means leaving the role with no permissions.
            'permissions' => ['present', 'array'],
            'permissions.*' => ['required', 'string', 'uuid'],
        ];
    }

    /**
     * Validation messages.
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
