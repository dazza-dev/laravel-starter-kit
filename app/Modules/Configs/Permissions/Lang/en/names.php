<?php

declare(strict_types=1);

return [
    // Etiqueta de cada módulo (el primer nivel de la matriz).
    'modules' => [
        'general' => 'General',
        'app' => 'Application',
        'configs' => 'Configuration',
    ],

    // Etiqueta de cada grupo de permisos (los nodos raiz del arbol).
    'groups' => [
        'dashboard' => 'Dashboard',
        'profile' => 'Profile',
        'users' => 'Users',
        'roles' => 'Roles',
        'groups' => 'Groups',
        'config' => 'Configuration',
    ],

    // Etiqueta de cada accion (los nodos hoja del arbol).
    'actions' => [
        'read-dashboard' => 'View dashboard',

        'read-profile' => 'View own profile',
        'update-profile' => 'Edit own profile',

        'create-users' => 'Create users',
        'read-users' => 'View users',
        'update-users' => 'Edit users',
        'delete-users' => 'Delete users',

        'create-roles' => 'Create roles',
        'read-roles' => 'View roles',
        'update-roles' => 'Edit roles',
        'delete-roles' => 'Delete roles',

        'create-groups' => 'Create groups',
        'read-groups' => 'View groups',
        'update-groups' => 'Edit groups',
        'delete-groups' => 'Delete groups',

        'read-config' => 'View settings',
        'update-config' => 'Edit settings',
    ],

];
