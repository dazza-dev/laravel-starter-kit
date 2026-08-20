<?php

declare(strict_types=1);

namespace App\Modules\Configs\Roles\Models;

use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'display_name', 'description'])]
class Role extends Model
{
    use HasUuid, SoftDeletes;

    /**
     * Roles del sistema, intocables (ni se eliminan ni se renombra su slug), deben coincidir con database/data/Roles.json.
     */
    public const SYSTEM_ROLES = ['admin', 'user'];

    /**
     * Indica si es uno de los roles predefinidos del sistema.
     */
    public function isSystemRole(): bool
    {
        return in_array($this->name, self::SYSTEM_ROLES, true);
    }
}
