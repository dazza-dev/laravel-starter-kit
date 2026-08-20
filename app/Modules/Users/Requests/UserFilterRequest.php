<?php

declare(strict_types=1);

namespace App\Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFilterRequest extends FormRequest
{
    /**
     * La autorización se resuelve en las rutas con el middleware permission:.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para el listado datatable de usuarios.
     */
    public function rules(): array
    {
        return dataTableFilterRules([
            'only_trashed' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string'],
        ]);
    }

    /**
     * Nombres legibles de los campos para los mensajes de error de validación.
     */
    public function attributes(): array
    {
        return dataTableFilterAttributes([
            'status' => __('users::validation.attributes.status'),
            'roles' => __('users::validation.attributes.role'),
        ]);
    }

    /**
     * Mensajes de validación personalizados para los campos del filtro datatable.
     */
    public function messages(): array
    {
        return dataTableFilterMessages();
    }
}
