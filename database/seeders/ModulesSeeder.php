<?php

namespace Database\Seeders;

use App\Helpers\JsonDataHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModulesSeeder extends Seeder
{
    public function run(): void
    {
        $data = JsonDataHelper::readJsonData(database_path('data/Modules.json'));

        if (empty($data)) {
            return;
        }

        $records = array_map(fn (array $row): array => [
            'uuid' => (string) Str::uuid(),
            'name' => $row['name'],
            'icon' => $row['icon'] ?? null,
            'order' => $row['order'] ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ], $data);

        DB::table('modules')->insert($records);
    }
}
