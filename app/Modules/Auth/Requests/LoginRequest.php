<?php

declare(strict_types=1);

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Logging in is public, so it's always allowed.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for authenticating with username and password.
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Custom validation messages for login.
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
     * Readable attribute names used in the error messages.
     */
    public function attributes(): array
    {
        return [
            'username' => __('auth::validation.attributes.username'),
            'password' => __('auth::validation.attributes.password'),
        ];
    }
}
