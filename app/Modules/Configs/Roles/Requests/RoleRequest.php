<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    /**
     * Every authenticated user can manage roles.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating or updating a role.
     */
    public function rules(): array
    {
        return [
            'display_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'display_name')->ignore($this->route('role'), 'uuid'),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Readable field names for validation error messages.
     */
    public function attributes(): array
    {
        return [
            'display_name' => __('roles::validation.attributes.display_name'),
            'description' => __('roles::validation.attributes.description'),
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'display_name.required' => __('roles::validation.display_name.required'),
            'display_name.max' => __('roles::validation.display_name.max'),
            'display_name.unique' => __('roles::validation.display_name.unique'),
            'description.max' => __('roles::validation.description.max'),
        ];
    }
}
