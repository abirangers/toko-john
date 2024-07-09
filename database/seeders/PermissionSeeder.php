<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'Users' => [
                ['name' => 'find-all-users', 'display_name' => 'Find All Users'],
                ['name' => 'find-user', 'display_name' => 'Find User'],
                ['name' => 'create-user', 'display_name' => 'Create User'],
                ['name' => 'update-user', 'display_name' => 'Update User'],
                ['name' => 'delete-user', 'display_name' => 'Delete User'],
                ['name' => 'bulk-delete-users', 'display_name' => 'Bulk Delete Users'],
            ],
            'Media' => [
                ['name' => 'find-all-media', 'display_name' => 'Find All Media'],
                ['name' => 'delete-media', 'display_name' => 'Delete Media'],
                ['name' => 'bulk-delete-media', 'display_name' => 'Bulk Delete Media'],
            ],
            'Categories' => [
                ['name' => 'find-all-categories', 'display_name' => 'Find All Categories'],
                ['name' => 'find-category', 'display_name' => 'Find Category'],
                ['name' => 'create-category', 'display_name' => 'Create Category'],
                ['name' => 'update-category', 'display_name' => 'Update Category'],
                ['name' => 'delete-category', 'display_name' => 'Delete Category'],
                ['name' => 'bulk-delete-categories', 'display_name' => 'Bulk Delete Categories'],
            ],
            'Products' => [
                ['name' => 'find-all-products', 'display_name' => 'Find All Products'],
                ['name' => 'find-product', 'display_name' => 'Find Product'],
                ['name' => 'create-product', 'display_name' => 'Create Product'],
                ['name' => 'update-product', 'display_name' => 'Update Product'],
                ['name' => 'delete-product', 'display_name' => 'Delete Product'],
                ['name' => 'bulk-delete-products', 'display_name' => 'Bulk Delete Products'],
            ],
            'Orders' => [
                ['name' => 'find-all-orders', 'display_name' => 'Find All Orders'],
                ['name' => 'find-order', 'display_name' => 'Find Order'],
                ['name' => 'create-order', 'display_name' => 'Create Order'],
                ['name' => 'update-order', 'display_name' => 'Update Order'],
                ['name' => 'delete-order', 'display_name' => 'Delete Order'],
                ['name' => 'bulk-delete-orders', 'display_name' => 'Bulk Delete Orders'],
            ],
            'Permissions' => [
                ['name' => 'find-all-permissions', 'display_name' => 'Find All Permissions'],
                ['name' => 'find-permission', 'display_name' => 'Find Permission'],
                ['name' => 'create-permission', 'display_name' => 'Create Permission'],
                ['name' => 'update-permission', 'display_name' => 'Update Permission'],
                ['name' => 'delete-permission', 'display_name' => 'Delete Permission'],
                ['name' => 'bulk-delete-permissions', 'display_name' => 'Bulk Delete Permissions'],
            ],
            'Roles' => [
                ['name' => 'find-all-roles', 'display_name' => 'Find All Roles'],
                ['name' => 'find-role', 'display_name' => 'Find Role'],
                ['name' => 'create-role', 'display_name' => 'Create Role'],
                ['name' => 'update-role', 'display_name' => 'Update Role'],
                ['name' => 'delete-role', 'display_name' => 'Delete Role'],
                ['name' => 'bulk-delete-roles', 'display_name' => 'Bulk Delete Roles'],
            ],
            'Permission Groups' => [
                ['name' => 'find-all-permission-groups', 'display_name' => 'Find All Permission Groups'],
                ['name' => 'find-permission-group', 'display_name' => 'Find Permission Group'],
                ['name' => 'create-permission-group', 'display_name' => 'Create Permission Group'],
                ['name' => 'update-permission-group', 'display_name' => 'Update Permission Group'],
                ['name' => 'delete-permission-group', 'display_name' => 'Delete Permission Group'],
                ['name' => 'bulk-delete-permission-groups', 'display_name' => 'Bulk Delete Permission Groups'],
            ],
        ];

        $adminRole = Role::where('name', 'admin')->first();
        $permissionsToSync = [];

        foreach ($permissions as $groupName => $groupPermissions) {
            foreach ($groupPermissions as $permission) {
                $createdPermission = Permission::create([
                    'name' => $permission['name'],
                    'display_name' => $permission['display_name'],
                    'group_name' => $groupName,
                    'guard_name' => 'web',
                ]);

                $permissionsToSync[] = $createdPermission->id;
            }
        }

        if ($adminRole) {
            $adminRole->syncPermissions($permissionsToSync);
        }
    }
}