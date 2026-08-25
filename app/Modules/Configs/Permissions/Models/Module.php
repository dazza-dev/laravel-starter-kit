<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Models;

use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'icon', 'order'])]
class Module extends Model
{
    use HasUuid;

    protected $connection = 'mysql';

    protected $casts = [
        'order' => 'integer',
    ];

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
