<?php

declare(strict_types=1);

return [
    'attributes' => [
        'settings' => 'settings',
        'settings.*.name' => 'name',
        'settings.*.value' => 'value',
        'file' => 'file',
        'type' => 'type',
    ],

    'settings' => [
        'required' => 'You must send at least one setting.',
        'array' => 'The settings format is not valid.',
    ],
    'settings_name' => [
        'required' => 'Each setting must have a name.',
        'string' => 'The setting name is not valid.',
    ],
    'settings_value' => [
        'present' => 'Each setting must include a value.',
    ],
    'file' => [
        'required' => 'You must select a file.',
        'file' => 'The file is not valid.',
        'image' => 'The file must be an image.',
        'max' => 'The image cannot exceed 2MB.',
    ],
];
