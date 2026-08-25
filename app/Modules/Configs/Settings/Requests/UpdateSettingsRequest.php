<?php

declare(strict_types=1);

namespace App\Modules\Configs\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Every authenticated user can update settings.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for a bulk settings update.
     */
    public function rules(): array
    {
        return [
            'settings' => ['required', 'array'],
            'settings.*.name' => ['required', 'string'],
            'settings.*.value' => ['present'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'settings.required' => __('settings::validation.settings.required'),
            'settings.array' => __('settings::validation.settings.array'),
            'settings.*.name.required' => __('settings::validation.settings_name.required'),
            'settings.*.name.string' => __('settings::validation.settings_name.string'),
            'settings.*.value.present' => __('settings::validation.settings_value.present'),
        ];
    }

    /**
     * Readable attribute names used in validation messages.
     */
    public function attributes(): array
    {
        return trans('settings::validation.attributes');
    }
}
