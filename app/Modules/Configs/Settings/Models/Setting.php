<?php

declare(strict_types=1);

namespace App\Modules\Configs\Settings\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'value', 'type'])]
class Setting extends Model
{
    public $timestamps = false;

    /**
     * Returns the value cast to its declared type.
     */
    public function getFormatValueAttribute(): mixed
    {
        $value = $this->value;

        return match ($this->type) {
            'integer' => filter_var($value, FILTER_VALIDATE_INT),
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'array' => json_decode((string) $value),
            default => $value,
        };
    }
}
