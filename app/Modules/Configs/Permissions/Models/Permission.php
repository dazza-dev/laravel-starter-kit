<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Models;

use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'module_id', 'group', 'order'])]
class Permission extends Model
{
    use HasUuid;

    protected $connection = 'mysql';

    protected $casts = [
        'order' => 'integer',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
