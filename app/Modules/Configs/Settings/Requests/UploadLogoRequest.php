<?php

declare(strict_types=1);

namespace App\Modules\Configs\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadLogoRequest extends FormRequest
{
    /**
     * Every authenticated user can upload a logo.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for the logo upload.
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'image', 'max:2048'],
            'type' => ['nullable', 'string'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'file.required' => __('settings::validation.file.required'),
            'file.file' => __('settings::validation.file.file'),
            'file.image' => __('settings::validation.file.image'),
            'file.max' => __('settings::validation.file.max'),
        ];
    }

    /**
     * Readable attribute names used in validation messages.
     */
    public function attributes(): array
    {
        return [
            'file' => __('settings::validation.attributes.file'),
            'type' => __('settings::validation.attributes.type'),
        ];
    }
}
