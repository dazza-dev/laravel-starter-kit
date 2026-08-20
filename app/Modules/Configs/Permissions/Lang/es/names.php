<?php

declare(strict_types=1);

return [
    // Etiqueta de cada módulo (el primer nivel de la matriz).
    'modules' => [
        'general' => 'General',
        'app' => 'Aplicación',
        'configs' => 'Configuración',
    ],

    // Etiqueta de cada grupo de permisos (los nodos raiz del arbol).
    'groups' => [
        'dashboard' => 'Dashboard',
        'profile' => 'Perfil',
        'users' => 'Usuarios',
        'roles' => 'Roles',
        'groups' => 'Grupos',
        'config' => 'Configuración',
    ],

    // Etiqueta de cada accion (los nodos hoja del arbol).
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
