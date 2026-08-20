<?php

declare(strict_types=1);

namespace App\Modules\Configs\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Todos los usuarios autenticados pueden actualizar la configuración.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para una actualización masiva de la configuración.
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
     * Mensajes de validación personalizados.
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
     * Nombres legibles de los atributos usados en los mensajes de validación.
     */
    public function attributes(): array
    {
        return trans('settings::validation.attributes');
    }
}
