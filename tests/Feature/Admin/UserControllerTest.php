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
    public function test_admin_can_access_index_users_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-users');

        $response = $this->actingAs($admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_index_users_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('find-all-users');

        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_index_users_view()
    {
        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_create_user_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-user');

        $response = $this->actingAs($admin)->get(route('admin.users.create'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_create_user_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('create-user');

        $response = $this->actingAs($user)->get(route('admin.users.create'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_create_user_view()
    {
        $response = $this->get(route('admin.users.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-user');

        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ];

        $response = $this->actingAs($admin)->post(route('admin.users.store'), $data);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User created successfully.');
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
        ]);
    }

    public function test_user_cannot_create_user()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('create-user');

        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'user',
        ];

        $response = $this->actingAs($user)->post(route('admin.users.store'), $data);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_create_user()
    {
        $response = $this->post(route('admin.users.store'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_user_with_admin_role()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-user');

        $data = [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'role' => 'admin',
        ];

        $response = $this->actingAs($admin)->post(route('admin.users.store'), $data);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User created successfully.');
        $this->assertDatabaseHas('users', [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
        ]);
    }

    public function test_admin_can_access_show_user_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-users');

        $user = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.show', $user->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_show_user_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('find-all-users');

        $user2 = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.users.show', $user->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_show_user_view()
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.show', $user->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_edit_user_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-user');

        $user = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.edit', $user->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_edit_user_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('update-user');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.users.edit', $user->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_edit_user_view()
    {
        $user = User::factory()->create();
        $response = $this->get(route('admin.users.edit', $user->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-user');

        $user = User::factory()->create();
        $user->assignRole('user');

        $data = [
            'name' => 'Updated User',
            'email' => 'updateduser@example.com',
            'password' => 'newpassword123',
            'confirm_password' => 'newpassword123',
            'role' => 'user',
        ];

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user->id), $data);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User updated successfully.');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated User',
            'email' => 'updateduser@example.com',
        ]);
    }

    public function test_user_cannot_update_user()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('update-user');

        $user = User::factory()->create();
        $user->assignRole('user');

        $data = [
            'name' => 'Updated User',
            'email' => 'updateduser@example.com',
            'password' => 'newpassword123',
            'confirm_password' => 'newpassword123',
            'role' => 'user',
        ];

        $response = $this->actingAs($user)->put(route('admin.users.update', $user->id), $data);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_update_user()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $data = [
            'name' => 'Updated User',
            'email' => 'updateduser@example.com',
            'password' => 'newpassword123',
            'confirm_password' => 'newpassword123',
            'role' => 'user',
        ];

        $response = $this->put(route('admin.users.update', $user->id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_user_with_admin_role()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-user');

        $user = User::factory()->create();
        $user->assignRole('admin');

        $data = [
            'name' => 'Updated Admin',
            'email' => 'updatedadmin@example.com',
            'password' => 'newpassword123',
            'confirm_password' => 'newpassword123',
            'role' => 'admin',
        ];

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user->id), $data);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User updated successfully.');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Admin',
            'email' => 'updatedadmin@example.com',
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('delete-user');

        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user->id));
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'User deleted successfully.');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_cannot_delete_user()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('delete-user');

        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->delete(route('admin.users.destroy', $user->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_delete()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $response = $this->delete(route('admin.users.destroy', $user->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_cannot_delete_user_with_admin_role()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('delete-user');

        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user->id));
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error', 'Cannot delete admin user.');
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_admin_can_bulk_delete_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('bulk-delete-users');

        $users = User::factory()->count(3)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
        $ids = $users->pluck('id')->toArray();

        $response = $this->actingAs($admin)->delete(route('admin.users.bulkDestroy'), ['ids' => $ids]);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'Bulk users deleted successfully.');
        $this->assertDatabaseMissing('users', ['id'=> $ids]);
    }

    public function test_user_cannot_bulk_delete_user()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('bulk-delete-users');

        $users = User::factory()->count(3)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
        $ids = $users->pluck('id')->toArray();

        $response = $this->actingAs($user)->delete(route('admin.users.bulkDestroy'), ['ids' => $ids]);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_bulk_delete_user()
    {
        $users = User::factory()->count(3)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
        $ids = $users->pluck('id')->toArray();

        $response = $this->delete(route('admin.users.bulkDestroy'), ['ids' => $ids]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_cannot_bulk_delete_user_with_admin_role()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('bulk-delete-users');

        $users = User::factory()->count(3)->create();
        foreach ($users as $user) {
            $user->assignRole('admin');
        }
        $ids = $users->pluck('id')->toArray();

        $response = $this->actingAs($admin)->delete(route('admin.users.bulkDestroy'), ['ids' => $ids]);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error', 'Cannot delete admin user.');
        $this->assertDatabaseHas('users', ['id' => $ids]);
    }
}