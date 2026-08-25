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
     * Keys visible without authentication.
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
     * Returns every setting as a key-value map; unauthenticated requests only get the public subset.
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
     * Bulk-updates settings from an array of {name, value} pairs.
     */
    public function bulkUpdate(array $settings): void
    {
        foreach ($settings as $item) {
            Setting::where('name', $item['name'])->update(['value' => $item['value']]);
        }
    }

    /**
     * Returns all active roles as {uuid, name, slug} for select inputs
     * (name = display_name, slug = the technical name used in URLs).
     */
    public function roles(): Collection
    {
        return Role::orderBy('display_name')
            ->get(['uuid', 'name', 'display_name'])
            ->map(fn ($r) => ['uuid' => $r->uuid, 'name' => $r->display_name, 'slug' => $r->name]);
    }

    /**
     * Returns all active groups as {uuid, name} for select inputs.
     */
    public function groups(): Collection
    {
        return Group::orderBy('name')->get(['uuid', 'name']);
    }

    /**
     * Stores an uploaded logo file and persists its URL in settings, under 'logo_dark' or 'logo' depending on $type.
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
