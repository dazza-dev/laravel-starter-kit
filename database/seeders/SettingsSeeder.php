<?php

namespace Database\Seeders;

use App\Helpers\JsonDataHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $data = JsonDataHelper::readJsonData(database_path('data/Settings.json'));
        if (! empty($data)) {
            DB::table('settings')->insertOrIgnore($data);
        }
    }
}
