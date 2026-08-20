<?php

declare(strict_types=1);

return [
    'attributes' => [
        'username' => 'username',
        'password' => 'password',
        'token' => 'token',
        'email' => 'email',
    ],

    'username' => [
        'required' => 'You must enter a username.',
        'string' => 'The username is not valid.',
    ],
    'password' => [
        'required' => 'You must enter a password.',
        'string' => 'The password is not valid.',
        'confirmed' => 'The passwords do not match.',
    ],
    'email' => [
        'required' => 'The email is required.',
        'string' => 'The email is not valid.',
        'email' => 'Enter a valid email address.',
    ],
    'token' => [
        'required' => 'You must provide the token.',
        'string' => 'The token is not valid.',
    ],
];
