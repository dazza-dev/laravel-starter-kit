<?php

declare(strict_types=1);

namespace App\Modules\Configs\Settings\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Configs\Settings\Requests\UpdateSettingsRequest;
use App\Modules\Configs\Settings\Requests\UploadLogoRequest;
use App\Modules\Configs\Settings\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __construct(private SettingsService $settingsService) {}

    /**
     * Devuelve la configuración como un mapa clave-valor; las claves públicas se exponen sin autenticar.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->settingsService->all(Auth::guard('web')->check()),
            'message' => 'OK',
        ]);
    }

    /**
     * Actualiza de forma masiva uno o más valores de configuración.
     */
    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        $this->settingsService->bulkUpdate($request->input('settings'));

        return response()->json(['message' => __('settings::messages.updated')]);
    }

    /**
     * Sube una imagen de logo y guarda su URL en la configuración.
     */
    public function uploadLogo(UploadLogoRequest $request): JsonResponse
    {
        $url = $this->settingsService->uploadLogo(
            $request->file('file'),
            $request->input('type')
        );

        return response()->json([
            'url' => $url,
            'message' => __('settings::messages.logo_uploaded'),
        ]);
    }

    /**
     * Devuelve todos los roles activos como {uuid, name} para inputs de selección.
     */
    public function roles(): JsonResponse
    {
        return response()->json(['data' => $this->settingsService->roles()]);
    }

    /**
     * Devuelve todos los grupos activos como {uuid, name} para inputs de selección.
     */
    public function groups(): JsonResponse
    {
        return response()->json(['data' => $this->settingsService->groups()]);
    }
}
