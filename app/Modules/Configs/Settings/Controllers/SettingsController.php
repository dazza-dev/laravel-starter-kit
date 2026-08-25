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
     * Returns the settings as a key-value map; public keys are exposed without authentication.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->settingsService->all(Auth::guard('web')->check()),
            'message' => 'OK',
        ]);
    }

    /**
     * Bulk-updates one or more settings values.
     */
    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        $this->settingsService->bulkUpdate($request->input('settings'));

        return response()->json(['message' => __('settings::messages.updated')]);
    }

    /**
     * Uploads a logo image and stores its URL in the settings.
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
     * Returns all active roles as {uuid, name} for select inputs.
     */
    public function roles(): JsonResponse
    {
        return response()->json(['data' => $this->settingsService->roles()]);
    }

    /**
     * Returns all active groups as {uuid, name} for select inputs.
     */
    public function groups(): JsonResponse
    {
        return response()->json(['data' => $this->settingsService->groups()]);
    }
}
