<?php

declare(strict_types=1);

return [
    'attributes' => [
        'permissions' => 'permissions',
    ],

    'permissions' => [
        'present' => 'You must send the list of permissions.',
        'array' => 'Permissions must be a list.',
        'uuid' => 'Each permission must be a valid identifier.',
    ],
];
