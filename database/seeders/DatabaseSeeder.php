<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            BadgeSeeder::class,
            VariantAttributeSeeder::class,
            AdminUserSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
