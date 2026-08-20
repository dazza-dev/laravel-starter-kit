<?php

declare(strict_types=1);

namespace App\Modules\Core\Traits;

use Illuminate\Support\Str;

/**
 * Genera un UUID al crear el modelo, para no exponer los ids internos.
 */
trait HasUuid
{
    /**
     * Inicializa el trait: asigna un UUID antes de persistir el modelo.
     */
    protected static function bootHasUuid(): void
    {
        static::creating(function (self $model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
