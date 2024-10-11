<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Controllers\Admin\UserCrudController;
use Illuminate\Foundation\Testing\RefreshDatabase;


class UserControllerTest extends AdminTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $controller = new UserCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_user_access()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-users', 'create-user', 'update-user', 'delete-user', 'bulk-delete-users');

        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ]);

        // Test admin can access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.users.{$view}", $view === 'show' || $view === 'edit' ? $user->id : null);
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }

        // Test admin can create user
        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Test User2',
            'email' => 'testuser2@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ]);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User created successfully');
        $this->assertDatabaseHas('users', ['name' => 'Test User2']);

        // Test admin can create user with admin role
        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'test admin',
            'email' => 'testadmin@gmail.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'admin',
        ]);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User created successfully');
        $this->assertDatabaseHas('users', ['name' => 'test admin']);

        // Test admin can update user
        $response = $this->actingAs($admin)->put(route('admin.users.update', $user->id), [
            'name' => 'Test User Updated',
            'email' => 'testuserupdated@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ]);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User updated successfully');
        $this->assertDatabaseHas('users', ['name' => 'Test User Updated']);

        // Test admin can update user with admin role
        $response = $this->actingAs($admin)->put(route('admin.users.update', $admin->id), [
            'name' => 'Test Admin Updated',
            'email' => 'testadminupdated@gmail.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'admin',
        ]);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User updated successfully');
        $this->assertDatabaseHas('users', ['name' => 'Test Admin Updated']);

        // Test admin can delete user
        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user->id));
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User deleted successfully');
        $this->assertDatabaseMissing('users', ['name' => 'Test User Updated']);

        // Test admin cannot delete user with admin role
        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin->id));
        $response->assertStatus(302);
        $response->assertSessionHas('error', 'You cannot delete yourself');
        $this->assertDatabaseHas('users', ['name' => 'Test Admin Updated']);

        // Test admin can delete multiple users
        $user2 = User::create([
            'name' => 'Test User New',
            'email' => 'testusernew@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.bulkDestroy'), ['ids' => [$user->id, $user2->id]]);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'Bulk users deleted successfully');
        $this->assertDatabaseMissing('users', ['name' => 'Test User']);
        $this->assertDatabaseMissing('users', ['name' => 'Test User New']);

        // Test admin cannot delete multiple users with admin role
        $admin2 = User::create([
            'name' => 'test admin2',
            'email' => 'testadmin2@gmail.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.bulkDestroy'), ['ids' => [$admin->id, $admin2->id]]);
        $response->assertStatus(302);
        $response->assertSessionHas('error', 'You cannot delete yourself');
        $this->assertDatabaseHas('users', ['name' => 'test admin']);
        $this->assertDatabaseHas('users', ['name' => 'test admin2']);
    }

    public function test_user_user_access()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
        ]);
        $user->assignRole('user');

        // Test user cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.users.{$view}", $view === 'show' || $view === 'edit' ? $user->id : null);
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(403);
        }

        // Test user cannot create user
        $response = $this->actingAs($user)->post(route('admin.users.store'), [
            'name' => 'Test User2',
            'email' => 'testuser2@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['name' => 'Test User']);

        // Test user cannot create user with admin role
        $response = $this->actingAs($user)->post(route('admin.users.store'), [
            'name' => 'test admin',
            'email' => 'testadmin@gmail.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'admin',
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['name' => 'Test User']);

        // Test user cannot update user
        $response = $this->actingAs($user)->put(route('admin.users.update', $user->id), [
            'name' => 'Test User Updated',
            'email' => 'testuserupdated@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['name' => 'Test User']);

        // Test user cannot update user with admin role
        $response = $this->actingAs($user)->put(route('admin.users.update', $user->id), [
            'name' => 'Test Admin Updated',
            'email' => 'testadminupdated@gmail.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'admin',
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['name' => 'Test User']);

        // Test user cannot delete user
        $response = $this->actingAs($user)->delete(route('admin.users.destroy', $user->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['name' => 'Test User']);

        // Test user cannot delete user with admin role
        $response = $this->actingAs($user)->delete(route('admin.users.destroy', $user->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['name' => 'Test User']);

        // Test user cannot delete multiple users
        $user2 = User::create([
            'name' => 'Test User New',
            'email' => 'testusernew@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.users.bulkDestroy'), ['ids' => [$user->id, $user2->id]]);
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['name' => 'Test User']);
        $this->assertDatabaseHas('users', ['name' => 'Test User New']);

        // Test user cannot delete multiple users with admin role
        $admin2 = User::create([
            'name' => 'test admin2',
            'email' => 'testadmin2@gmail.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.users.bulkDestroy'), ['ids' => [$user->id, $admin2->id]]);
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['name' => 'Test User']);
        $this->assertDatabaseHas('users', ['name' => 'test admin2']);
    }

    public function test_guest_user_access()
    {
        $user_id = User::factory()->create()->id;

        // Test user cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.users.{$view}", $view === 'show' || $view === 'edit' ? $user_id : null);
            $response = $this->get($route);
            $response->assertStatus(302)->assertRedirect(route('login'));
        }

        // Test user cannot create user
        $response = $this->post(route('admin.users.store'), [
            'name' => 'Test User2',
            'email' => 'testuser2@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ]);
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseMissing('users', ['name' => 'Test User2']);

        // Test user cannot create user with admin role
        $response = $this->post(route('admin.users.store'), [
            'name' => 'test admin',
            'email' => 'testadmin@gmail.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'admin',
        ]);
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseMissing('users', ['name' => 'test admin']);

        // Test user cannot update user
        $response = $this->put(route('admin.users.update', $user_id), [
            'name' => 'Test User Updated',
            'email' => 'testuserupdated@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ]);
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseMissing('users', ['name' => 'Test User Updated']);

        // Test user cannot update user with admin role
        $response = $this->put(route('admin.users.update', $user_id), [
            'name' => 'Test Admin Updated',
            'email' => 'testadminupdated@gmail.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'admin',
        ]);
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseMissing('users', ['name' => 'Test Admin Updated']);

        // Test user cannot delete user
        $response = $this->delete(route('admin.users.destroy', $user_id));
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseMissing('users', ['name' => 'Test User']);

        // Test user cannot delete user with admin role
        $response = $this->delete(route('admin.users.destroy', $user_id));
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', ['id' => $user_id]);

        // Test user cannot delete multiple users
        $user2 = User::create([
            'name' => 'Test User New',
            'email' => 'testusernew@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ]);

        $response = $this->delete(route('admin.users.bulkDestroy'), ['ids' => [$user_id, $user2->id]]);
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', ['id' => $user_id]);
        $this->assertDatabaseHas('users', ['name' => 'Test User New']);

        // Test user cannot delete multiple users with admin role
        $admin2 = User::create([
            'name' => 'test admin2',
            'email' => 'testadmin2@gmail.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'admin',
        ]);

        $response = $this->delete(route('admin.users.bulkDestroy'), ['ids' => [$user_id, $admin2->id]]);
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', ['id' => $user_id]);
        $this->assertDatabaseHas('users', ['name' => 'test admin2']);
    }
}