<?php

declare(strict_types=1);

namespace App\Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * La autorización se resuelve en las rutas con el middleware permission:.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para crear o actualizar un usuario: la contraseña es obligatoria solo al crear.
     */
    public function rules(): array
    {
        $uuid = $this->route('uuid');
        $isCreating = $uuid === null;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($uuid, 'uuid')->whereNull('deleted_at')],
            'phone' => ['nullable', 'string', 'max:30'],
            'username' => ['required', 'string', 'max:60', Rule::unique('users', 'username')->ignore($uuid, 'uuid')->whereNull('deleted_at')],
            'password' => [$isCreating ? 'required' : 'nullable', 'string', 'min:8', 'max:255'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'role_uuids' => ['required', 'array', 'min:1'],
            'role_uuids.*' => ['string', Rule::exists('roles', 'uuid')->whereNull('deleted_at')],
        ];
    }

    /**
     * Nombres legibles de los campos para los mensajes de error de validación.
     */
    public function attributes(): array
    {
        return [
            'first_name' => __('users::validation.attributes.first_name'),
            'last_name' => __('users::validation.attributes.last_name'),
            'email' => __('users::validation.attributes.email'),
            'phone' => __('users::validation.attributes.phone'),
            'username' => __('users::validation.attributes.username'),
            'password' => __('users::validation.attributes.password'),
            'role_uuids' => __('users::validation.attributes.role'),
        ];
    }

    /**
     * Mensajes de validación personalizados.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => __('users::validation.first_name.required'),
            'email.required' => __('users::validation.email.required'),
            'email.email' => __('users::validation.email.email'),
            'email.unique' => __('users::validation.email.unique'),
            'username.required' => __('users::validation.username.required'),
            'username.unique' => __('users::validation.username.unique'),
            'password.required' => __('users::validation.password.required'),
            'password.min' => __('users::validation.password.min'),
            'role_uuids.required' => __('users::validation.role.required'),
            'role_uuids.min' => __('users::validation.role.required'),
            'role_uuids.*.exists' => __('users::validation.role.exists'),
        ];
    }
}
