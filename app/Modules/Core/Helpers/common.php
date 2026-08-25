<?php

declare(strict_types=1);

if (! function_exists('module_path')) {
    function module_path(string $module, string $path = ''): string
    {
        return app_path("Modules/{$module}").($path ? '/'.$path : '');
    }
}
