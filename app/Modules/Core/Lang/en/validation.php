<?php

declare(strict_types=1);

return [
    'attributes' => [
        'search' => 'search',
        'page' => 'page',
        'per_page' => 'items per page',
        'sort_by' => 'sort order',
    ],

    'search' => [
        'max' => 'The search term may not exceed :max characters.',
    ],

    'page' => [
        'integer' => 'The page must be a valid number.',
        'min' => 'The page must be at least 1.',
    ],

    'per_page' => [
        'integer' => 'Items per page must be a valid number.',
        'min' => 'Items per page must be at least 1.',
        'max' => 'Items per page may not exceed :max.',
    ],
];
