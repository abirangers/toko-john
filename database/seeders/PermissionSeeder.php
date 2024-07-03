<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'find-all-user',
                'display_name' => 'Find All User',
                'group_name' => 'User',
                'guard_name' => 'web',
            ],
            [
                'name' => 'find-user',
                'display_name' => 'Find User',
                'group_name' => 'User',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create-user',
                'display_name' => 'Create User',
                'group_name' => 'User',
                'guard_name' => 'web',
            ],
            [
                'name' => 'update-user',
                'display_name' => 'Update User',
                'group_name' => 'User',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete-user',
                'display_name' => 'Delete User',
                'group_name' => 'User',
                'guard_name' => 'web',
            ],
            [
                'name' => 'bulk-delete-user',
                'display_name' => 'Bulk Delete User',
                'group_name' => 'User',
                'guard_name' => 'web',
            ],
            
            [
                'name' => 'find-all-media',
                'display_name' => 'Find All Media',
                'group_name' => 'Media',
                'guard_name' => 'web',
            ],
            [
                'name' => 'find-media',
                'display_name' => 'Find Media',
                'group_name' => 'Media',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create-media',
                'display_name' => 'Create Media',
                'group_name' => 'Media',
                'guard_name' => 'web',
            ],
            [
                'name' => 'update-media',
                'display_name' => 'Update Media',
                'group_name' => 'Media',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete-media',
                'display_name' => 'Delete Media',
                'group_name' => 'Media',
                'guard_name' => 'web',
            ],
            [
                'name' => 'bulk-delete-media',
                'display_name' => 'Bulk Delete Media',
                'group_name' => 'Media',
                'guard_name' => 'web',
            ],

            [
                'name' => 'find-all-class',
                'display_name' => 'Find All Class',
                'group_name' => 'Class',
                'guard_name' => 'web',
            ],
            [
                'name' => 'find-class',
                'display_name' => 'Find Class',
                'group_name' => 'Class',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create-class',
                'display_name' => 'Create Class',
                'group_name' => 'Class',
                'guard_name' => 'web',
            ],
            [
                'name' => 'update-class',
                'display_name' => 'Update Class',
                'group_name' => 'Class',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete-class',
                'display_name' => 'Delete Class',
                'group_name' => 'Class',
                'guard_name' => 'web',
            ],
            [
                'name' => 'bulk-delete-class',
                'display_name' => 'Bulk Delete Class',
                'group_name' => 'Class',
                'guard_name' => 'web',
            ],

            [
                'name' => 'find-all-major',
                'display_name' => 'Find All Major',
                'group_name' => 'Major',
                'guard_name' => 'web',
            ],
            [
                'name' => 'find-major',
                'display_name' => 'Find Major',
                'group_name' => 'Major',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create-major',
                'display_name' => 'Create Major',
                'group_name' => 'Major',
                'guard_name' => 'web',
            ],
            [
                'name' => 'update-major',
                'display_name' => 'Update Major',
                'group_name' => 'Major',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete-major',
                'display_name' => 'Delete Major',
                'group_name' => 'Major',
                'guard_name' => 'web',
            ],
            [
                'name' => 'bulk-delete-major',
                'display_name' => 'Bulk Delete Major',
                'group_name' => 'Major',
                'guard_name' => 'web',
            ],

            [
                'name' => 'find-all-category',
                'display_name' => 'Find All Category',
                'group_name' => 'Category',
                'guard_name' => 'web',
            ],
            [
                'name' => 'find-category',
                'display_name' => 'Find Category',
                'group_name' => 'Category',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create-category',
                'display_name' => 'Create Category',
                'group_name' => 'Category',
                'guard_name' => 'web',
            ],  
            [
                'name' => 'update-category',
                'display_name' => 'Update Category',
                'group_name' => 'Category',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete-category',
                'display_name' => 'Delete Category',
                'group_name' => 'Category',
                'guard_name' => 'web',
            ],
            [
                'name' => 'bulk-delete-category',
                'display_name' => 'Bulk Delete Category',
                'group_name' => 'Category',
                'guard_name' => 'web',
            ],
            
            [
                'name' => 'find-all-product',
                'display_name' => 'Find All Product',
                'group_name' => 'Product',
                'guard_name' => 'web',
            ],
            [
                'name' => 'find-product',
                'display_name' => 'Find Product',
                'group_name' => 'Product',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create-product',
                'display_name' => 'Create Product',
                'group_name' => 'Product',
                'guard_name' => 'web',
            ],
            [
                'name' => 'update-product',
                'display_name' => 'Update Product',
                'group_name' => 'Product',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete-product',
                'display_name' => 'Delete Product',
                'group_name' => 'Product',
                'guard_name' => 'web',
            ],
            [
                'name' => 'bulk-delete-product',
                'display_name' => 'Bulk Delete Product',
                'group_name' => 'Product',
                'guard_name' => 'web',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'group_name' => $permission['group_name'],
                'guard_name' => $permission['guard_name'],
            ]);
        }
    }
}