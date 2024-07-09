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
            'categories' => 'Categories',
            'products' => 'Products',
            'users' => 'Users',
            'orders' => 'Orders',
            'permissions' => 'Permissions',
            'roles' => 'Roles',
            'permission_groups' => 'Permission Groups',
        ];

        foreach ($groups as $group) {
            PermissionGroup::create(['name' => $group]);
        }
    }
}