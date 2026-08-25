<?php

declare(strict_types=1);

namespace App\Modules\Core\Traits;

use Illuminate\Support\Str;

/**
 * Generates a UUID on model creation, so internal ids are never exposed.
 */
trait HasUuid
{
    /**
     * Assigns a UUID before the model is persisted.
     */
    protected static function bootHasUuid(): void
    {
        static::creating(function (self $model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
