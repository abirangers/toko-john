<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PermissionGroup;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            'media' => 'Media',
            'major' => 'Major',
            'class' => 'Class',
            'category' => 'Category',
            'product' => 'Product',
            'user' => 'User',
        ];

        foreach ($groups as $group) {
            PermissionGroup::create(['name' => $group]);
        }
    }
}