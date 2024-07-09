<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\PermissionCrudController;
use Illuminate\Routing\Controllers\HasMiddleware;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionControllerTest extends AdminTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $controller = new PermissionCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_can_access_index_permissions_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-permissions');

        $response = $this->actingAs($admin)->get(route('admin.permissions.index'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_index_permissions_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('admin.permissions.index'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_index_permissions_view()
    {
        $response = $this->get(route('admin.permissions.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_create_permission_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-permission');

        $response = $this->actingAs($admin)->get(route('admin.permissions.create'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_create_permission_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('admin.permissions.store'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_create_permission_view()
    {
        $response = $this->get(route('admin.permissions.store'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_permission()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-permission');

        $response = $this->actingAs($admin)->post(route('admin.permissions.store'), [
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);
        $response->assertRedirect(route('admin.permissions.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('permissions', [
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);
    }

    public function test_user_cannot_create_permission()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->post(route('admin.permissions.store'), [
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_create_permission()
    {
        $response = $this->post(route('admin.permissions.store'), [
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_show_permission_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-permission');

        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.permissions.show', $permission->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_show_permission_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->actingAs($user)->get(route('admin.permissions.show', $permission->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_show_permission_view()
    {
        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->get(route('admin.permissions.show', $permission->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_edit_permission_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-permission');

        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.permissions.edit', $permission->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_edit_permission_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->actingAs($user)->get(route('admin.permissions.edit', $permission->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_edit_permission_view()
    {
        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->get(route('admin.permissions.edit', $permission->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_permission()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-permission');

        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.permissions.update', $permission->id), [
            'name' => 'test-permission-updated',
            'display_name' => 'Test Permission Updated',
            'group_name' => 'Test Group Updated',
        ]);
        $response->assertRedirect(route('admin.permissions.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('permissions', [
            'name' => 'test-permission-updated',
            'display_name' => 'Test Permission Updated',
            'group_name' => 'Test Group Updated',
        ]);
    }

    public function test_user_cannot_update_permission()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->actingAs($user)->put(route('admin.permissions.update', $permission->id), [
            'name' => 'test-permission-updated',
            'display_name' => 'Test Permission Updated',
            'group_name' => 'Test Group Updated',
        ]);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_update_permission()
    {
        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->put(route('admin.permissions.update', $permission->id), [
            'name' => 'test-permission-updated',
            'display_name' => 'Test Permission Updated',
            'group_name' => 'Test Group Updated',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_delete_permission()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('delete-permission');

        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.permissions.destroy', $permission->id));
        $response->assertRedirect(route('admin.permissions.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('permissions', [
            'name' => 'test-permission',
        ]);
    }

    public function test_user_cannot_delete_permission()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.permissions.destroy', $permission->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('permissions', [
            'name' => 'test-permission',
        ]);
    }

    public function test_guest_cannot_delete_permission()
    {
        $permission = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $response = $this->delete(route('admin.permissions.destroy', $permission->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('permissions', [
            'name' => 'test-permission',
        ]);
    }

    public function test_admin_can_bulk_delete_permissions()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('bulk-delete-permissions');

        $permission1 = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $permission2 = Permission::create([
            'name' => 'test-permission2',
            'display_name' => 'Test Permission2',
            'group_name' => 'Test Group2',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.permissions.bulkDestroy', ['ids' => [$permission1->id, $permission2->id]]));
        $response->assertRedirect(route('admin.permissions.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('permissions', [
            'name' => 'test-permission',
        ]);
        $this->assertDatabaseMissing('permissions', [
            'name' => 'test-permission2',
        ]);
    }

    public function test_user_cannot_bulk_delete_permissions()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $permission1 = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $permission2 = Permission::create([
            'name' => 'test-permission2',
            'display_name' => 'Test Permission2',
            'group_name' => 'Test Group2',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.permissions.bulkDestroy', ['ids' => [$permission1->id, $permission2->id]]));
        $response->assertStatus(403);
        $this->assertDatabaseHas('permissions', [
            'name' => 'test-permission',
        ]);
        $this->assertDatabaseHas('permissions', [
            'name' => 'test-permission2',
        ]);
    }

    public function test_guest_cannot_bulk_delete_permissions()
    {
        $permission1 = Permission::create([
            'name' => 'test-permission',
            'display_name' => 'Test Permission',
            'group_name' => 'Test Group',
        ]);

        $permission2 = Permission::create([
            'name' => 'test-permission2',
            'display_name' => 'Test Permission2',
            'group_name' => 'Test Group2',
        ]);

        $response = $this->delete(route('admin.permissions.bulkDestroy', ['ids' => [$permission1->id, $permission2->id]]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('permissions', [
            'name' => 'test-permission',
        ]);
        $this->assertDatabaseHas('permissions', [
            'name' => 'test-permission2',
        ]);
    }

}
