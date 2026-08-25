<?php

use App\Http\Middleware\HorizonBasicAuth;
use App\Modules\Configs\Permissions\Middleware\CheckPermission;
use App\Modules\Core\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Session support on API routes enables cookie-based auth for the SPA.
        // SetLocale reads the frontend's Accept-Language header to respond in that language.
        $middleware->api(append: [StartSession::class, SetLocale::class]);

        $middleware->alias([
            'permission' => CheckPermission::class,
            'horizon.auth' => HorizonBasicAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
