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
     * Finds a user by username.
     */
    public function findByUsername(string $username): ?User
    {
        return User::where('username', $username)->first();
    }

    /**
     * Validates the credentials and returns the user, or throws if they fail.
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
     * Updates the authenticated user's profile fields and returns the reloaded model.
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->fill($data)->save();

        return $user->fresh();
    }

    /**
     * Sends the password reset email and returns the `Password` broker status.
     */
    public function sendResetLink(string $email): string
    {
        return Password::broker()->sendResetLink(['email' => $email]);
    }

    /**
     * Changes the password if the token is valid and rotates remember_token to drop open sessions.
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
