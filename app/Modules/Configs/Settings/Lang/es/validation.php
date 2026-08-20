<?php

declare(strict_types=1);

return [
    'attributes' => [
        'settings' => 'configuraciones',
        'settings.*.name' => 'nombre',
        'settings.*.value' => 'valor',
        'file' => 'archivo',
        'type' => 'tipo',
    ],

    'settings' => [
        'required' => 'Debes enviar al menos una configuración.',
        'array' => 'El formato de configuraciones no es válido.',
    ],
    'settings_name' => [
        'required' => 'Cada configuración debe tener un nombre.',
        'string' => 'El nombre de la configuración no es válido.',
    ],
    'settings_value' => [
        'present' => 'Cada configuración debe incluir un valor.',
    ],
    'file' => [
        'required' => 'Debes seleccionar un archivo.',
        'file' => 'El archivo no es válido.',
        'image' => 'El archivo debe ser una imagen.',
        'max' => 'La imagen no puede superar los 2MB.',
    ],
];
