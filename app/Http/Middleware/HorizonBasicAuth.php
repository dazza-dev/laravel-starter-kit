<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protege el panel de Horizon con autenticación básica.
 */
class HorizonBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = (string) config('horizon.basic_auth.username');
        $password = (string) config('horizon.basic_auth.password');

        // Sin credenciales configuradas, el panel queda cerrado.
        if ($user === '' || $password === '') {
            return $this->deny();
        }

        [$givenUser, $givenPassword] = $this->credentials($request);

        // hash_equals y sin cortocircuito, para no filtrar nada por tiempo de respuesta.
        $userOk = hash_equals($user, $givenUser);
        $passwordOk = hash_equals($password, $givenPassword);

        return $userOk && $passwordOk ? $next($request) : $this->deny();
    }

    /**
     * Lee las credenciales de la cabecera Authorization, que bajo PHP-FPM es la única fiable.
     *
     * @return array{0: string, 1: string}
     */
    private function credentials(Request $request): array
    {
        $header = (string) $request->header('Authorization');

        if (! preg_match('/^Basic\s+(.+)$/i', $header, $matches)) {
            return ['', ''];
        }

        $decoded = base64_decode($matches[1], true);

        if ($decoded === false || ! str_contains($decoded, ':')) {
            return ['', ''];
        }

        return explode(':', $decoded, 2);
    }

    private function deny(): Response
    {
        return response('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic realm="Horizon"']);
    }
}
