<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Dominios de producción de la SPA. Añade aquí el tuyo al desplegar.
    'allowed_origins' => array_filter([
        env('FRONTEND_URL'),
    ]),

    // Accepts http://localhost on any port for local development.
    'allowed_origins_patterns' => [
        '/^http:\/\/localhost(:\d+)?$/',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Required for cookie-based session auth from the SPA.
    'supports_credentials' => true,
];
