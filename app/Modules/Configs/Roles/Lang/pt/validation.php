<?php

declare(strict_types=1);

return [
    'attributes' => [
        'display_name' => 'nome da função',
        'description' => 'descrição',
    ],

    'display_name' => [
        'required' => 'O nome da função é obrigatório.',
        'max' => 'O nome da função não pode ultrapassar :max caracteres.',
        'unique' => 'Já existe uma função com esse nome.',
    ],

    'description' => [
        'max' => 'A descrição não pode ultrapassar :max caracteres.',
    ],
];
