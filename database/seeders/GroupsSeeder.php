<?php

namespace Database\Seeders;

use App\Helpers\JsonDataHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupsSeeder extends Seeder
{
    public function run(): void
    {
        $data = JsonDataHelper::readJsonData(database_path('data/Groups.json'));
        if (! empty($data)) {
            $records = array_map(function (array $row): array {
                return [
                    'uuid' => (string) Str::uuid(),
                    'name' => $row['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $data);

            DB::table('groups')->insert($records);
        }
    }
}
