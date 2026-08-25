<?php

declare(strict_types=1);

return [
    'attributes' => [
        'username' => 'usuario',
        'password' => 'contraseña',
        'token' => 'token',
        'email' => 'correo',
    ],

    'username' => [
        'required' => 'Debes ingresar el usuario.',
        'string' => 'El usuario no es válido.',
    ],
    'password' => [
        'required' => 'Debes ingresar la contraseña.',
        'string' => 'La contraseña no es válida.',
        'confirmed' => 'Las contraseñas no coinciden.',
    ],
    'email' => [
        'required' => 'Debes ingresar el correo.',
        'string' => 'El correo no es válido.',
        'email' => 'Debes ingresar un correo válido.',
    ],
    'token' => [
        'required' => 'Debes proporcionar el token.',
        'string' => 'El token no es válido.',
    ],
];
