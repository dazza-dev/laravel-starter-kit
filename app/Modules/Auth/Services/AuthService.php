<?php

declare(strict_types=1);

namespace App\Modules\Auth\Services;

use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Busca un usuario por su nombre de usuario.
     */
    public function findByUsername(string $username): ?User
    {
        return User::where('username', $username)->first();
    }

    /**
     * Valida las credenciales y devuelve el usuario, o lanza una excepción si fallan.
     */
    public function attemptLogin(string $username, string $password): User
    {
        $user = $this->findByUsername($username);

        if (! $user) {
            throw new \Exception(__('auth::messages.failed'));
        }

        if (! Hash::check($password, $user->password)) {
            throw new \Exception(__('auth::messages.failed'));
        }

        if ($user->status !== 'active') {
            throw new \Exception(__('auth::messages.not_allowed'));
        }

        $user->forceFill(['last_login_at' => now()])->save();

        return $user;
    }

    /**
     * Actualiza los campos del perfil del usuario autenticado y devuelve el modelo recargado.
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->fill($data)->save();

        return $user->fresh();
    }

    /**
     * Envía el correo con el enlace para restablecer la contraseña y devuelve el estado de `Password`.
     */
    public function sendResetLink(string $email): string
    {
        return Password::broker()->sendResetLink(['email' => $email]);
    }

    /**
     * Cambia la contraseña si el token es válido y renueva el remember_token para tumbar las sesiones abiertas.
     *
     * @param  array<string, string>  $credentials
     */
    public function resetPassword(array $credentials): string
    {
        return Password::broker()->reset($credentials, function (User $user, string $password) {
            $user->forceFill([
                'password' => $password,
                'remember_token' => Str::random(60),
            ])->save();
        });
    }
}
