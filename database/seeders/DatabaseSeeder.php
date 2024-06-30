<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ClassModel;
use App\Models\Major;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        $this->call([
            MajorSeeder::class,
            ClassSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}