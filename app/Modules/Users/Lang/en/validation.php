<?php

declare(strict_types=1);

return [
    'attributes' => [
        'first_name' => 'first name',
        'last_name' => 'last name',
        'email' => 'email',
        'phone' => 'phone',
        'username' => 'username',
        'password' => 'password',
        'status' => 'status',
        'role' => 'role',
        'group' => 'group',
    ],

    'first_name' => [
        'required' => 'The first name is required.',
    ],

    'email' => [
        'required' => 'The email is required.',
        'email' => 'The email format is not valid.',
        'unique' => 'A user with this email already exists.',
    ],

    'username' => [
        'required' => 'The username is required.',
        'unique' => 'A user with this username already exists.',
    ],

    'password' => [
        'required' => 'The password is required.',
        'min' => 'The password must be at least :min characters.',
    ],

    'role' => [
        'required' => 'The role is required.',
        'exists' => 'The selected role does not exist.',
    ],

    'status' => [
        'in' => 'The status is not valid.',
    ],
];
