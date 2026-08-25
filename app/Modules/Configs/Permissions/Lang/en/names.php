<?php

declare(strict_types=1);

return [
    // Label for each module (the matrix's first level).
    'modules' => [
        'general' => 'General',
        'app' => 'Application',
        'configs' => 'Configuration',
    ],

    // Label for each permission group (the tree's root nodes).
    'groups' => [
        'dashboard' => 'Dashboard',
        'profile' => 'Profile',
        'users' => 'Users',
        'roles' => 'Roles',
        'groups' => 'Groups',
        'config' => 'Configuration',
    ],

    // Label for each action (the tree's leaf nodes).
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
