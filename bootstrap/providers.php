<?php

use App\Modules\Auth\AuthServiceProvider;
use App\Modules\Configs\Groups\GroupsServiceProvider;
use App\Modules\Configs\Permissions\PermissionsServiceProvider;
use App\Modules\Configs\Roles\RolesServiceProvider;
use App\Modules\Configs\Settings\SettingsServiceProvider;
use App\Modules\Core\CoreServiceProvider;
use App\Modules\Files\FilesServiceProvider;
use App\Modules\Users\UsersServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\HorizonServiceProvider;

return [
    AppServiceProvider::class,

    // Core — must be first (registers module_path helper)
    CoreServiceProvider::class,

    // Modules
    AuthServiceProvider::class,
    UsersServiceProvider::class,
    GroupsServiceProvider::class,
    FilesServiceProvider::class,
    PermissionsServiceProvider::class,
    RolesServiceProvider::class,
    SettingsServiceProvider::class,

    HorizonServiceProvider::class,
];
