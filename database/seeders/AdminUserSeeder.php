<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@apinsdigital.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncRoles(['super_admin']);

        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@apinsdigital.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['admin']);

        // Editor
        $editor = User::updateOrCreate(
            ['email' => 'editor@apinsdigital.com'],
            [
                'name' => 'Editor User',
                'password' => bcrypt('password123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $editor->syncRoles(['editor']);

        $this->command->info('Admin users created:');
        $this->command->info('  Super Admin: superadmin@apinsdigital.com / password123');
        $this->command->info('  Admin: admin@apinsdigital.com / password123');
        $this->command->info('  Editor: editor@apinsdigital.com / password123');
    }
}
