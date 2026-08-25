<?php

namespace Database\Seeders;

use App\Helpers\JsonDataHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AclSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRoles();
        $this->assignPermissionsToAdministrator();
    }

    /**
     * Creates the roles in the database.
     */
    private function seedRoles(): void
    {
        $data = JsonDataHelper::readJsonData(database_path('data/Roles.json'));

        if (empty($data)) {
            return;
        }

        $records = array_map(function (array $row): array {
            return [
                'uuid' => (string) Str::uuid(),
                'name' => $row['name'],
                'display_name' => $row['display_name'] ?? null,
                'description' => $row['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $data);

        DB::table('roles')->insert($records);
    }

    /**
     * Assigns every permission to the administrator role.
     */
    private function assignPermissionsToAdministrator(): void
    {
        $administratorRole = DB::table('roles')
            ->where('name', 'admin')
            ->first();

        if (! $administratorRole) {
            return;
        }

        $permissionIds = DB::table('permissions')
            ->pluck('id');

        $records = $permissionIds->map(fn ($permissionId) => [
            'role_id' => $administratorRole->id,
            'permission_id' => $permissionId,
        ])->toArray();

        DB::table('permission_role')->insert($records);
    }
}
