<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{
    /**
     * Every authenticated user can manage groups.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating or updating a group.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('groups', 'name')->ignore($this->route('group'), 'uuid')],
        ];
    }

    /**
     * Custom validation messages.
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
