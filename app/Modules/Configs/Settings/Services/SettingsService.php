<?php

declare(strict_types=1);

namespace App\Modules\Configs\Settings\Services;

use App\Modules\Configs\Groups\Models\Group;
use App\Modules\Configs\Roles\Models\Role;
use App\Modules\Configs\Settings\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SettingsService
{
    /**
     * Claves visibles sin autenticar.
     */
    private const PUBLIC_KEYS = [
        'js_dateformat',
        'js_datetime_format',
        'language',
        'logo',
        'logo_dark',
        'app_name',
        'app_theme',
        'timezone',
    ];

    /**
     * Devuelve toda la configuración como un mapa clave-valor; sin autenticar solo llega el subconjunto público.
     */
    public function all(bool $isAuthenticated = false): Collection
    {
        return Setting::all(['name', 'value', 'type'])
            ->when(
                ! $isAuthenticated,
                fn ($col) => $col->whereIn('name', self::PUBLIC_KEYS)
            )
            ->pluck('format_value', 'name');
    }

    /**
     * Actualiza de forma masiva la configuración a partir de un arreglo de pares {name, value}.
     */
    public function bulkUpdate(array $settings): void
    {
        foreach ($settings as $item) {
            Setting::where('name', $item['name'])->update(['value' => $item['value']]);
        }
    }

    /**
     * Devuelve todos los roles activos como {uuid, name, slug} para inputs de selección
     * (name = display_name, slug = nombre técnico usado en las URLs).
     */
    public function roles(): Collection
    {
        return Role::orderBy('display_name')
            ->get(['uuid', 'name', 'display_name'])
            ->map(fn ($r) => ['uuid' => $r->uuid, 'name' => $r->display_name, 'slug' => $r->name]);
    }

    /**
     * Devuelve todos los grupos activos como {uuid, name} para inputs de selección.
     */
    public function groups(): Collection
    {
        return Group::orderBy('name')->get(['uuid', 'name']);
    }

    /**
     * Guarda un archivo de logo subido y persiste su URL en la configuración, bajo 'logo_dark' o 'logo' según $type.
     */
    public function uploadLogo(UploadedFile $file, ?string $type): string
    {
        $path = $file->store('logos', 'public');
        $url = Storage::url($path);

        $key = $type === 'dark' ? 'logo_dark' : 'logo';
        Setting::where('name', $key)->update(['value' => $url]);

        return $url;
    }
}
