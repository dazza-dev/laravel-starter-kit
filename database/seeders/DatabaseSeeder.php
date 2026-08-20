<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Credenciales del primer usuario: cámbialas antes de sembrar en un entorno real.
     */
    private const ADMIN_EMAIL = 'admin@example.test';

    private const ADMIN_PASSWORD = 'password123';

    public function run(): void
    {
        // Modules y Permissions van primero: el resto cuelga de ellos.
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
     * Crea el usuario con el que se entra por primera vez.
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
