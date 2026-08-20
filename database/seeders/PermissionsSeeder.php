<?php

namespace Database\Seeders;

use App\Helpers\JsonDataHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $data = JsonDataHelper::readJsonData(database_path('data/Permissions.json'));

        if (empty($data)) {
            return;
        }

        $modules = DB::table('modules')->pluck('id', 'name');

        $records = array_map(fn (array $row): array => [
            'uuid' => (string) Str::uuid(),
            'name' => $row['name'],
            'module_id' => $row['module'] ? $modules[$row['module']] ?? null : null,
            'group' => $row['group'],
            'order' => $row['order'] ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ], $data);

        DB::table('permissions')->insert($records);
    }
}
