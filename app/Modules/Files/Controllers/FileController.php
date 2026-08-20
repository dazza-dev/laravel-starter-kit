<?php

declare(strict_types=1);

namespace App\Modules\Files\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    private const SEGMENT = '/^[A-Za-z0-9_-]+(\.[A-Za-z0-9_-]+)*$/';

    /**
     * Sirve un fichero del disco privado sin exponer su ruta real; requiere sesión.
     */
    public function show(Request $request, string $folder, string $filename): StreamedResponse
    {
        // El segmento no puede empezar por punto ni ser `..`, o se saldría del directorio.
        if (! preg_match(self::SEGMENT, $folder) || ! preg_match(self::SEGMENT, $filename)) {
            abort(404, __('files::messages.not_found'));
        }

        $disk = Storage::disk('local');
        $path = "{$folder}/{$filename}";

        if (! $disk->exists($path)) {
            abort(404, __('files::messages.not_found'));
        }

        return $disk->response($path, $filename, [
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
            'Cross-Origin-Resource-Policy' => 'cross-origin',
        ]);
    }
}
