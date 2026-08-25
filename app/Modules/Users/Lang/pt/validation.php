<?php

declare(strict_types=1);

return [
    'attributes' => [
        'first_name' => 'nome',
        'last_name' => 'sobrenome',
        'email' => 'e-mail',
        'phone' => 'telefone',
        'username' => 'nome de usuário',
        'password' => 'senha',
        'status' => 'situação',
        'role' => 'função',
        'group' => 'grupo',
    ],

    'first_name' => [
        'required' => 'O nome é obrigatório.',
    ],

    'email' => [
        'required' => 'O e-mail é obrigatório.',
        'email' => 'O formato do e-mail não é válido.',
        'unique' => 'Já existe um usuário com esse e-mail.',
    ],

    'username' => [
        'required' => 'O nome de usuário é obrigatório.',
        'unique' => 'Já existe um usuário com esse nome de usuário.',
    ],

    'password' => [
        'required' => 'A senha é obrigatória.',
        'min' => 'A senha deve ter no mínimo :min caracteres.',
    ],

    'role' => [
        'required' => 'A função é obrigatória.',
        'exists' => 'A função selecionada não existe.',
    ],

    'status' => [
        'in' => 'O status não é válido.',
    ],
];
