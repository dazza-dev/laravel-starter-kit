<?php

declare(strict_types=1);

return [
    'attributes' => [
        'display_name' => 'role name',
        'description' => 'description',
    ],

    'display_name' => [
        'required' => 'The role name is required.',
        'max' => 'The role name may not exceed :max characters.',
        'unique' => 'A role with this name already exists.',
    ],

    'description' => [
        'max' => 'The description may not exceed :max characters.',
    ],
];
