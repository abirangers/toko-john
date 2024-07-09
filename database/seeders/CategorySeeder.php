<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Bed', 'slug' => 'bed'],
            ['name' => 'Trolley', 'slug' => 'trolley'],
            ['name' => 'Cabinet', 'slug' => 'cabinet']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}