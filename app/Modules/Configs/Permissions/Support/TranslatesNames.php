<?php

declare(strict_types=1);

namespace App\Modules\Configs\Permissions\Support;

use Illuminate\Support\Str;

trait TranslatesNames
{
    /**
     * Translates a catalog key, or the readable name if the permission was created from the panel and has no translation yet.
     */
    protected function translateName(string $bucket, ?string $name): string
    {
        if ($name === null) {
            return '';
        }

        $key = 'permissions::names.'.$bucket.'.'.$name;
        $label = __($key);

        return $label === $key ? Str::headline($name) : $label;
    }
}
