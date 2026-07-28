<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            SettingSeeder::class,
            SiteConfigSeeder::class,
            ServiceSeeder::class,
            PortfolioSeeder::class,
            BlogSeeder::class,
            PeopleSeeder::class,
            InteractionSeeder::class,
        ]);
    }
}
