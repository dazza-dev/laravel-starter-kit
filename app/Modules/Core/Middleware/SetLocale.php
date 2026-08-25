<?php

declare(strict_types=1);

namespace App\Modules\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Locales supported by the app (must match the frontend's).
     */
    private const SUPPORTED = ['es', 'en', 'pt'];

    /**
     * Sets the locale from the Accept-Language header.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader('Accept-Language')) {
            $locale = $request->getPreferredLanguage(self::SUPPORTED);

            if ($locale) {
                App::setLocale($locale);
            }
        }

        return $next($request);
    }
}
