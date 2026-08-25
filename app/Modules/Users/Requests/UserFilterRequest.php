<?php

declare(strict_types=1);

namespace App\Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFilterRequest extends FormRequest
{
    /**
     * Authorization is resolved in the routes with the permission: middleware.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for the users datatable listing.
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
     * Readable field names for validation error messages.
     */
    public function attributes(): array
    {
        return dataTableFilterAttributes([
            'status' => __('users::validation.attributes.status'),
            'roles' => __('users::validation.attributes.role'),
        ]);
    }

    /**
     * Custom validation messages for the datatable filter fields.
     */
    public function messages(): array
    {
        return dataTableFilterMessages();
    }
}
