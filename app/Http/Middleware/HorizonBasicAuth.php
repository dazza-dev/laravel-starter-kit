<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protects the Horizon panel with basic authentication.
 */
class HorizonBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = (string) config('horizon.basic_auth.username');
        $password = (string) config('horizon.basic_auth.password');

        // Panel stays locked if no credentials are configured.
        if ($user === '' || $password === '') {
            return $this->deny();
        }

        [$givenUser, $givenPassword] = $this->credentials($request);

        // hash_equals with no short-circuit, so response time leaks nothing.
        $userOk = hash_equals($user, $givenUser);
        $passwordOk = hash_equals($password, $givenPassword);

        return $userOk && $passwordOk ? $next($request) : $this->deny();
    }

    /**
     * Reads credentials from the Authorization header, the only reliable source under PHP-FPM.
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
