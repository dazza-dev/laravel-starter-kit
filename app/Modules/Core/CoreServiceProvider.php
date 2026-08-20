<?php

declare(strict_types=1);

namespace App\Modules\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        require_once __DIR__.'/Helpers/common.php';
        require_once __DIR__.'/Helpers/datatable.php';
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/Lang', 'core');
    }
}
