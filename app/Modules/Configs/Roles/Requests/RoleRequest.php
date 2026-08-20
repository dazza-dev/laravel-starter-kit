<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    /**
     * Todos los usuarios autenticados pueden gestionar roles.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para crear o actualizar un rol.
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
     * Nombres legibles de los campos para los mensajes de error de validación.
     */
    public function attributes(): array
    {
        return [
            'display_name' => __('roles::validation.attributes.display_name'),
            'description' => __('roles::validation.attributes.description'),
        ];
    }

    /**
     * Mensajes de validación personalizados.
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
