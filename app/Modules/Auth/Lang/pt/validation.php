<?php

declare(strict_types=1);

return [
    'attributes' => [
        'username' => 'usuário',
        'password' => 'senha',
        'token' => 'token',
        'email' => 'e-mail',
    ],

    'username' => [
        'required' => 'Você deve informar o usuário.',
        'string' => 'O usuário não é válido.',
    ],
    'password' => [
        'required' => 'Você deve informar a senha.',
        'string' => 'A senha não é válida.',
        'confirmed' => 'As senhas não coincidem.',
    ],
    'email' => [
        'required' => 'Você deve informar o e-mail.',
        'string' => 'O e-mail não é válido.',
        'email' => 'Informe um e-mail válido.',
    ],
    'token' => [
        'required' => 'Você deve fornecer o token.',
        'string' => 'O token não é válido.',
    ],
];
