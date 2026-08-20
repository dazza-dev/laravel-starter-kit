<?php

declare(strict_types=1);

namespace App\Modules\Configs\Groups\Models;

use App\Modules\Core\Traits\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name'])]
class Group extends Model
{
    use HasUuid, SoftDeletes;
}
