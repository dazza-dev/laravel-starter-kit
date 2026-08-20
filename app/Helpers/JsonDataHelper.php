<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Lee los ficheros JSON con los datos de las semillas.
 */
class JsonDataHelper
{
    /**
     * Devuelve el contenido decodificado, o un array vacío si el fichero no sirve.
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
