<?php

declare(strict_types=1);

namespace App\Modules\Users\Models;

use App\Modules\Auth\Notifications\ResetPasswordNotification;
use App\Modules\Configs\Permissions\Services\PermissionService;
use App\Modules\Configs\Roles\Models\Role;
use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['first_name', 'last_name', 'email', 'phone', 'username', 'password', 'avatar', 'status', 'created_by'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, HasUuid, Notifiable, SoftDeletes;

    // Slug del rol con acceso total. Su bypass se aplica en PermissionsServiceProvider vía Gate::before.
    public const ROLE_ADMIN = 'admin';

    /**
     * Permisos efectivos, cacheados por request.
     *
     * @var array<int, string>|null
     */
    private ?array $permissionNames = null;

    /**
     * La contraseña se hashea automáticamente al asignarla.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Roles del usuario, cuyos permisos se suman.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Indica si el usuario tiene alguno de los roles con acceso total.
     */
    public function isAdmin(): bool
    {
        return $this->roles->contains('name', self::ROLE_ADMIN);
    }

    /**
     * Nombre completo del usuario; cae al username cuando no hay nombre y apellido.
     */
    public function fullName(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? '')) ?: (string) $this->username;
    }

    /**
     * Nombres de los permisos efectivos del usuario.
     *
     * @return array<int, string>
     */
    public function permissionNames(): array
    {
        if ($this->permissionNames === null) {
            $this->permissionNames = app(PermissionService::class)
                ->namesForUser($this->id, $this->roles->pluck('id')->all());
        }

        return $this->permissionNames;
    }

    /**
     * Indica si el usuario tiene un permiso concreto.
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissionNames(), true);
    }

    /**
     * Se sobreescribe para que el enlace apunte a la SPA.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
