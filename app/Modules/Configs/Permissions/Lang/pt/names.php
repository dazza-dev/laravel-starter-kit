<?php

declare(strict_types=1);

return [
    // Etiqueta de cada módulo (el primer nivel de la matriz).
    'modules' => [
        'general' => 'Geral',
        'app' => 'Aplicação',
        'configs' => 'Configuração',
    ],

    // Etiqueta de cada grupo de permisos (los nodos raiz del arbol).
    'groups' => [
        'dashboard' => 'Dashboard',
        'profile' => 'Perfil',
        'users' => 'Usuários',
        'roles' => 'Funções',
        'groups' => 'Grupos',
        'config' => 'Configuração',
    ],

    // Etiqueta de cada accion (los nodos hoja del arbol).
    'actions' => [
        'read-dashboard' => 'Ver o dashboard',

        'read-profile' => 'Ver o próprio perfil',
        'update-profile' => 'Editar o próprio perfil',

        'create-users' => 'Criar usuários',
        'read-users' => 'Ver usuários',
        'update-users' => 'Editar usuários',
        'delete-users' => 'Excluir usuários',

        'create-roles' => 'Criar funções',
        'read-roles' => 'Ver funções',
        'update-roles' => 'Editar funções',
        'delete-roles' => 'Excluir funções',

        'create-groups' => 'Criar grupos',
        'read-groups' => 'Ver grupos',
        'update-groups' => 'Editar grupos',
        'delete-groups' => 'Excluir grupos',

        'read-config' => 'Ver a configuração',
        'update-config' => 'Editar a configuração',
    ],

];
