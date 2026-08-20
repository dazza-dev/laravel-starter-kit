<?php

declare(strict_types=1);

return [
    'attributes' => [
        'name' => 'name',
    ],

    'name' => [
        'required' => 'The group name is required.',
        'max' => 'The name may not exceed :max characters.',
        'unique' => 'A group with this name already exists.',
    ],
];
