<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * First-user credentials: change these before seeding a real environment.
     */
    private const ADMIN_EMAIL = 'admin@example.test';

    private const ADMIN_PASSWORD = 'password123';

    public function run(): void
    {
        // Modules and Permissions come first: everything else depends on them.
        $this->call([
            ModulesSeeder::class,
            PermissionsSeeder::class,
            AclSeeder::class,
            GroupsSeeder::class,
            SettingsSeeder::class,
        ]);

        $this->createAdminUser();
    }

    /**
     * Creates the first user to log in with.
     */
    private function createAdminUser(): void
    {
        $email = self::ADMIN_EMAIL;

        if (User::where('email', $email)->exists()) {
            return;
        }

        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => $email,
            'username' => $email,
            'password' => self::ADMIN_PASSWORD,
            'status' => 'active',
        ]);

        $role = DB::table('roles')->where('name', 'admin')->first();

        if ($role) {
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        }
    }
}
