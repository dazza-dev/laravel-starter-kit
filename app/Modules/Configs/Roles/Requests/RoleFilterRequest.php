<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleFilterRequest extends FormRequest
{
    /**
     * Todos los usuarios autenticados pueden listar roles.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para el listado datatable de roles.
     */
    public function rules(): array
    {
        return dataTableFilterRules([
            'only_trashed' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Nombres legibles de los campos para los mensajes de error de validación.
     */
    public function attributes(): array
    {
        return dataTableFilterAttributes();
    }

    /**
     * Mensajes de validación personalizados para los campos del filtro datatable.
     */
    public function messages(): array
    {
        return dataTableFilterMessages();
    }
}
