<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\RoleCrudController;
use Illuminate\Routing\Controllers\HasMiddleware;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class RoleControllerTest extends AdminTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $controller = new RoleCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_can_access_roles_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-roles');

        $response = $this->actingAs($admin)->get(route('admin.roles.index'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_roles_index()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('admin.roles.index'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_roles_index()
    {
        $response = $this->get(route('admin.roles.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_create_role_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-role');

        $response = $this->actingAs($admin)->get(route('admin.roles.create'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_create_role_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('admin.roles.create'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_create_role_view()
    {
        $response = $this->get(route('admin.roles.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_role()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-role');

        $response = $this->actingAs($admin)->post(route('admin.roles.store'), [
            'name' => 'test',
            'display_name' => 'test',
        ]);
        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success', 'Role created successfully');
        $this->assertDatabaseHas('roles', ['name' => 'test']);
    }    

    public function test_user_cannot_create_role()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->post(route('admin.roles.store'), [
            'name' => 'test',
            'display_name' => 'test',
        ]);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_create_role()
    {
        $response = $this->post(route('admin.roles.store'), [
            'name' => 'test',
            'display_name' => 'test',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_show_role_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-role');

        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.roles.show', $role->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_show_role_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->actingAs($user)->get(route('admin.roles.show', $role->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_show_role_view()
    {
        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->get(route('admin.roles.show', $role->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_edit_role_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-role');

        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.roles.edit', $role->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_edit_role_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->actingAs($user)->get(route('admin.roles.edit', $role->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_edit_role_view()
    {
        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->get(route('admin.roles.edit', $role->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_role()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-role');

        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.roles.update', $role->id), [
            'name' => 'test2',
            'display_name' => 'test2',
        ]);
        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success', 'Role updated successfully');
        $this->assertDatabaseHas('roles', ['name' => 'test2']);
    }

    public function test_user_cannot_update_role()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->actingAs($user)->put(route('admin.roles.update', $role->id), [
            'name' => 'test2',
            'display_name' => 'test2',
        ]);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_update_role()
    {
        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->put(route('admin.roles.update', $role->id), [
            'name' => 'test2',
            'display_name' => 'test2',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_delete_role()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('delete-role');

        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.roles.destroy', $role->id));
        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success', 'Role deleted successfully');
        $this->assertDatabaseMissing('roles', ['name' => 'test']);
    }

    public function test_user_cannot_delete_role()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.roles.destroy', $role->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('roles', ['name' => 'test']);
    }

    public function test_guest_cannot_delete_role()
    {
        $role = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $response = $this->delete(route('admin.roles.destroy', $role->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('roles', ['name' => 'test']);
    }

    public function test_admin_can_bulk_delete_roles()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('bulk-delete-roles');

        $roles1 = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);
        $roles2 = Role::create([
            'name' => 'test2',
            'display_name' => 'test2',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.roles.bulkDestroy'), ['ids' => [$roles1->id, $roles2->id]]);
        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success', 'Bulk roles deleted successfully.');
        $this->assertDatabaseMissing('roles', ['id' => $roles1->id]);
        $this->assertDatabaseMissing('roles', ['id' => $roles2->id]);
    }

    public function test_user_cannot_bulk_delete_roles()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $roles1 = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);
        $roles2 = Role::create([
            'name' => 'test2',
            'display_name' => 'test2',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.roles.bulkDestroy'), ['ids' => [$roles1->id, $roles2->id]]);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_bulk_delete_roles()
    {
        $roles1 = Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);
        $roles2 = Role::create([
            'name' => 'test2',
            'display_name' => 'test2',
        ]);

        $response = $this->delete(route('admin.roles.bulkDestroy'), ['ids' => [$roles1->id, $roles2->id]]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
