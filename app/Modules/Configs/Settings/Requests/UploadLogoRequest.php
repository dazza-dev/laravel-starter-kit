<?php

declare(strict_types=1);

namespace App\Modules\Configs\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadLogoRequest extends FormRequest
{
    /**
     * Todos los usuarios autenticados pueden subir un logo.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para la subida del logo.
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'image', 'max:2048'],
            'type' => ['nullable', 'string'],
        ];
    }

    /**
     * Mensajes de validación personalizados.
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
     * Nombres legibles de los atributos usados en los mensajes de validación.
     */
    public function attributes(): array
    {
        return [
            'file' => __('settings::validation.attributes.file'),
            'type' => __('settings::validation.attributes.type'),
        ];
    }
}
