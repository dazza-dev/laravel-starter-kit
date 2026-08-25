<?php

declare(strict_types=1);

return [
    'attributes' => [
        'name' => 'nombre',
    ],

    'name' => [
        'required' => 'El nombre del grupo es obligatorio.',
        'max' => 'El nombre no puede superar los :max caracteres.',
        'unique' => 'Ya existe un grupo con ese nombre.',
    ],
];
