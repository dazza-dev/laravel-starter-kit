<?php

declare(strict_types=1);

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    /**
     * The emailed token is the credential, so the route is public.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'token.required' => __('auth::validation.token.required'),
            'token.string' => __('auth::validation.token.string'),
            'email.required' => __('auth::validation.email.required'),
            'email.string' => __('auth::validation.email.string'),
            'email.email' => __('auth::validation.email.email'),
            'password.required' => __('auth::validation.password.required'),
            'password.string' => __('auth::validation.password.string'),
            'password.confirmed' => __('auth::validation.password.confirmed'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'token' => __('auth::validation.attributes.token'),
            'email' => __('auth::validation.attributes.email'),
            'password' => __('auth::validation.attributes.password'),
        ];
    }
}
