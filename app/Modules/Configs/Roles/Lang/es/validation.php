<?php

declare(strict_types=1);

return [
    'attributes' => [
        'display_name' => 'nombre del rol',
        'description' => 'descripción',
    ],

    'display_name' => [
        'required' => 'El nombre del rol es obligatorio.',
        'max' => 'El nombre del rol no puede superar los :max caracteres.',
        'unique' => 'Ya existe un rol con ese nombre.',
    ],

    'description' => [
        'max' => 'La descripción no puede superar los :max caracteres.',
    ],
];
