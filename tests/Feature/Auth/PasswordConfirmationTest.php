<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\PermissionGroupSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class); // Seed roles before each test
        $this->seed(PermissionSeeder::class);
        $this->seed(PermissionGroupSeeder::class);
    }

    public function test_confirm_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('user');
        
        $response = $this->actingAs($user)->get('/confirm-password');
        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('user');

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('user');

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}