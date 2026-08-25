<?php

declare(strict_types=1);

return [
    // Label for each module (the matrix's first level).
    'modules' => [
        'general' => 'General',
        'app' => 'Aplicación',
        'configs' => 'Configuración',
    ],

    // Label for each permission group (the tree's root nodes).
    'groups' => [
        'dashboard' => 'Dashboard',
        'profile' => 'Perfil',
        'users' => 'Usuarios',
        'roles' => 'Roles',
        'groups' => 'Grupos',
        'config' => 'Configuración',
    ],

    // Label for each action (the tree's leaf nodes).
    'actions' => [
        'read-dashboard' => 'Ver el dashboard',

        'read-profile' => 'Ver el perfil propio',
        'update-profile' => 'Editar el perfil propio',

        'create-users' => 'Crear usuarios',
        'read-users' => 'Ver usuarios',
        'update-users' => 'Editar usuarios',
        'delete-users' => 'Eliminar usuarios',

        'create-roles' => 'Crear roles',
        'read-roles' => 'Ver roles',
        'update-roles' => 'Editar roles',
        'delete-roles' => 'Eliminar roles',

        'create-groups' => 'Crear grupos',
        'read-groups' => 'Ver grupos',
        'update-groups' => 'Editar grupos',
        'delete-groups' => 'Eliminar grupos',

        'read-config' => 'Ver la configuración',
        'update-config' => 'Editar la configuración',
    ],

];
