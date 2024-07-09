<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PermissionSeeder;
use Spatie\Permission\PermissionRegistrar;
use Database\Seeders\PermissionGroupSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class); // Seed roles before each test
        $this->seed(PermissionSeeder::class);
        $this->seed(PermissionGroupSeeder::class);

        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}