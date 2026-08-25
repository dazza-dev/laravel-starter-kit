<?php

declare(strict_types=1);

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    /**
     * Only your own profile can be updated.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * All fields are optional; username and email must be unique except for the record itself.
     */
    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'username' => ['nullable', 'string', 'max:60', Rule::unique('users', 'username')->ignore($this->user()->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
