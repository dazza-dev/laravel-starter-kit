<?php

declare(strict_types=1);

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\ForgotPasswordRequest;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\ProfileRequest;
use App\Modules\Auth\Requests\ResetPasswordRequest;
use App\Modules\Auth\Resources\ProfileResource;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * Autentica con usuario/contraseña e inicia una sesión.
     */
    public function login(LoginRequest $request): Response
    {
        try {
            $user = $this->authService->attemptLogin(
                $request->input('username'),
                $request->input('password')
            );
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 422);
        }

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return response([
            'data' => ProfileResource::make($user->load('roles')),
            'message' => __('auth::messages.login_success'),
        ]);
    }

    /**
     * Invalida la sesión actual.
     */
    public function logout(Request $request): Response
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response(['message' => __('auth::messages.logout_success')]);
    }

    /**
     * Devuelve el perfil del usuario autenticado.
     */
    public function profile(Request $request): Response
    {
        return response([
            'data' => ProfileResource::make($request->user()->load('roles')),
        ]);
    }

    /**
     * Actualiza el perfil del usuario autenticado.
     */
    public function updateProfile(ProfileRequest $request): Response
    {
        $user = $request->user();
        $data = array_filter($request->only([
            'email', 'cellphone', 'gender', 'username',
        ]), fn ($v) => $v !== null);

        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }

        $updated = $this->authService->updateProfile($user, $data);

        return response([
            'data' => ProfileResource::make($updated->load('roles')),
            'message' => __('auth::messages.profile_updated'),
        ]);
    }

    /**
     * Envía el enlace para restablecer la contraseña.
     */
    public function forgotPassword(ForgotPasswordRequest $request): Response
    {
        $status = $this->authService->sendResetLink($request->validated('email'));

        if ($status === Password::RESET_THROTTLED) {
            return response(['message' => __('auth::messages.reset_throttled')], 429);
        }

        // La respuesta es la misma exista o no la cuenta, para no revelar qué correos están registrados.
        return response(['message' => __('auth::messages.reset_link_sent')]);
    }

    /**
     * Cambia la contraseña a partir del token recibido por correo.
     */
    public function resetPassword(ResetPasswordRequest $request): Response
    {
        $status = $this->authService->resetPassword(
            $request->only('email', 'password', 'password_confirmation', 'token')
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response(['message' => __('auth::messages.reset_invalid')], 422);
        }

        return response(['message' => __('auth::messages.reset_success')]);
    }
}
