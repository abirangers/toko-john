<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionGroupSeeder::class,
            UserSeeder::class,
            MajorSeeder::class,
            ClassSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}