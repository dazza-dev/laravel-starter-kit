<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{
    /**
     * Todos los usuarios autenticados pueden gestionar grupos.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para crear o actualizar un grupo.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('groups', 'name')->ignore($this->route('group'), 'uuid')],
        ];
    }

    /**
     * Mensajes de validación personalizados.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('groups::validation.name.required'),
            'name.max' => __('groups::validation.name.max'),
            'name.unique' => __('groups::validation.name.unique'),
        ];
    }
}
