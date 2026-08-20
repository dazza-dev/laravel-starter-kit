<?php

declare(strict_types=1);

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * El inicio de sesión es público, por lo que siempre se permite.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para autenticar con usuario y contraseña.
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Mensajes de validación personalizados para el inicio de sesión.
     */
    public function messages(): array
    {
        return [
            'username.required' => __('auth::validation.username.required'),
            'username.string' => __('auth::validation.username.string'),
            'password.required' => __('auth::validation.password.required'),
            'password.string' => __('auth::validation.password.string'),
        ];
    }

    /**
     * Nombres legibles de los atributos usados en los mensajes de error.
     */
    public function attributes(): array
    {
        return [
            'username' => __('auth::validation.attributes.username'),
            'password' => __('auth::validation.attributes.password'),
        ];
    }
}
