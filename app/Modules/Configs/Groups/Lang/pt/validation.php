<?php

declare(strict_types=1);

return [
    'attributes' => [
        'name' => 'nome',
    ],

    'name' => [
        'required' => 'O nome do grupo é obrigatório.',
        'max' => 'O nome não pode ultrapassar :max caracteres.',
        'unique' => 'Já existe um grupo com esse nome.',
    ],
];
