<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Reads the JSON files that hold the seed data.
 */
class JsonDataHelper
{
    /**
     * Returns the decoded content, or an empty array if the file is unusable.
     */
    public static function readJsonData(string $path): array
    {
        if (! is_file($path)) {
            return [];
        }

        $content = file_get_contents($path);

        if ($content === false) {
            return [];
        }

        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : [];
    }
}
