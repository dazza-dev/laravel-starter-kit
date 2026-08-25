<?php

declare(strict_types=1);

return [
    'attributes' => [
        'search' => 'busca',
        'page' => 'página',
        'per_page' => 'registros por página',
        'sort_by' => 'ordenação',
    ],

    'search' => [
        'max' => 'O termo de busca não pode exceder :max caracteres.',
    ],

    'page' => [
        'integer' => 'A página deve ser um número válido.',
        'min' => 'A página deve ser pelo menos 1.',
    ],

    'per_page' => [
        'integer' => 'Os registros por página devem ser um número válido.',
        'min' => 'Os registros por página devem ser pelo menos 1.',
        'max' => 'Os registros por página não podem exceder :max.',
    ],

    'sort_by' => [
        'order' => 'A ordenação não é válida.',
    ],
];
