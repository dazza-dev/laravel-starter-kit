<?php

declare(strict_types=1);

return [
    'attributes' => [
        'search' => 'búsqueda',
        'page' => 'página',
        'per_page' => 'registros por página',
        'sort_by' => 'orden',
    ],

    'search' => [
        'max' => 'El término de búsqueda no puede superar los :max caracteres.',
    ],

    'page' => [
        'integer' => 'La página debe ser un número válido.',
        'min' => 'La página debe ser al menos 1.',
    ],

    'per_page' => [
        'integer' => 'Los registros por página deben ser un número válido.',
        'min' => 'Los registros por página deben ser al menos 1.',
        'max' => 'Los registros por página no pueden superar :max.',
    ],

    'sort_by' => [
        'order' => 'El orden no es válido.',
    ],
];
