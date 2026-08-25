<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleFilterRequest extends FormRequest
{
    /**
     * Every authenticated user can list roles.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for the roles datatable listing.
     */
    public function rules(): array
    {
        return dataTableFilterRules([
            'only_trashed' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Readable field names for validation error messages.
     */
    public function attributes(): array
    {
        return dataTableFilterAttributes();
    }

    /**
     * Custom validation messages for the datatable filter fields.
     */
    public function messages(): array
    {
        return dataTableFilterMessages();
    }
}
