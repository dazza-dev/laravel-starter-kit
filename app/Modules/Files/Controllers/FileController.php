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
     * Serves a file from the private disk without exposing its real path; requires a session.
     */
    public function show(Request $request, string $folder, string $filename): StreamedResponse
    {
        // A segment can't start with a dot or be `..`, or it would escape the directory.
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
