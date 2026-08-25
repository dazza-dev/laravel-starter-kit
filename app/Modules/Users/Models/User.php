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

    // Slug of the full-access role. Its bypass is applied in PermissionsServiceProvider via Gate::before.
    public const ROLE_ADMIN = 'admin';

    /**
     * Effective permissions, cached per request.
     *
     * @var array<int, string>|null
     */
    private ?array $permissionNames = null;

    /**
     * The password is hashed automatically when assigned.
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
     * The user's roles, whose permissions add up.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Whether the user has one of the full-access roles.
     */
    public function isAdmin(): bool
    {
        return $this->roles->contains('name', self::ROLE_ADMIN);
    }

    /**
     * The user's full name; falls back to the username when there's no first/last name.
     */
    public function fullName(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? '')) ?: (string) $this->username;
    }

    /**
     * Names of the user's effective permissions.
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
     * Whether the user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissionNames(), true);
    }

    /**
     * Overridden so the link points to the SPA.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
