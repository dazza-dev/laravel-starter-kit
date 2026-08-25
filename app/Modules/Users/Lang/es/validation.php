<?php

declare(strict_types=1);

return [
    'attributes' => [
        'first_name' => 'nombre',
        'last_name' => 'apellido',
        'email' => 'correo electrónico',
        'phone' => 'teléfono',
        'username' => 'nombre de usuario',
        'password' => 'contraseña',
        'status' => 'estado',
        'role' => 'rol',
        'group' => 'grupo',
    ],

    'first_name' => [
        'required' => 'El nombre es obligatorio.',
    ],

    'email' => [
        'required' => 'El correo electrónico es obligatorio.',
        'email' => 'El formato del correo electrónico no es válido.',
        'unique' => 'Ya existe un usuario con ese correo electrónico.',
    ],

    'username' => [
        'required' => 'El nombre de usuario es obligatorio.',
        'unique' => 'Ya existe un usuario con ese nombre de usuario.',
    ],

    'password' => [
        'required' => 'La contraseña es obligatoria.',
        'min' => 'La contraseña debe tener al menos :min caracteres.',
    ],

    'role' => [
        'required' => 'El rol es obligatorio.',
        'exists' => 'El rol seleccionado no existe.',
    ],

    'status' => [
        'in' => 'El estado no es válido.',
    ],
];
