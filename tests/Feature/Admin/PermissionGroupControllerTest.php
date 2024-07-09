<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\PermissionGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Admin\PermissionGroupCrudController;

class PermissionGroupControllerTest extends AdminTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $controller = new PermissionGroupCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_can_access_index_permission_group_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-permission-groups');

        $response = $this->actingAs($admin)->get(route('admin.permission-groups.index'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_index_permission_group_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('find-all-permission-groups');

        $response = $this->actingAs($user)->get(route('admin.permission-groups.index'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_index_permission_group_view()
    {
        $response = $this->get(route('admin.permission-groups.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_create_permission_group_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-permission-group');

        $response = $this->actingAs($admin)->get(route('admin.permission-groups.create'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_create_permission_group_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('create-permission-group');

        $response = $this->actingAs($user)->get(route('admin.permission-groups.create'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_create_permission_group_view()
    {
        $response = $this->get(route('admin.permission-groups.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_permission_group()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-permission-group');

        $permissionGroup = [
            'name' => 'Test Permission Group',
        ];

        $response = $this->actingAs($admin)->post(route('admin.permission-groups.store'), $permissionGroup);
        $response->assertRedirect(route('admin.permission-groups.index'));
        $this->assertDatabaseHas('permission_groups', [
            'name' => 'Test Permission Group',
        ]);
    }

    public function test_user_cannot_create_permission_group()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('create-permission-group');

        $permissionGroup = [
            'name' => 'Test Permission Group',
        ];

        $response = $this->actingAs($user)->post(route('admin.permission-groups.store'), $permissionGroup);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('permission_groups', [
            'name' => 'Test Permission Group',
        ]);
    }

    public function test_guest_cannot_create_permission_group()
    {
        $permissionGroup = [
            'name' => 'Test Permission Group',
        ];

        $response = $this->post(route('admin.permission-groups.store'), $permissionGroup);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('permission_groups', [
            'name' => 'Test Permission Group',
        ]);
    }

    public function test_admin_can_access_show_permission_group_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-permission-group');

        $permissionGroup = PermissionGroup::create([
            'name' => 'Test Permission Group',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.permission-groups.show', $permissionGroup->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_show_permission_group_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('find-permission-group');

        $permissionGroup = PermissionGroup::create([
            'name' => 'Test Permission Group',
        ]);

        $response = $this->actingAs($user)->get(route('admin.permission-groups.show', $permissionGroup->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_show_permission_group_view()
    {
        $permissionGroup = PermissionGroup::create([
            'name' => 'Test Permission Group',
        ]);

        $response = $this->get(route('admin.permission-groups.show', $permissionGroup->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_permission_group()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-permission-group');

        $permissionGroup = PermissionGroup::create([
            'name' => 'Test Permission Group',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.permission-groups.update', $permissionGroup->id), [
            'name' => 'Updated Permission Group',
        ]);
        $response->assertRedirect(route('admin.permission-groups.index'));
        $this->assertDatabaseHas('permission_groups', [
            'name' => 'Updated Permission Group',
        ]);
    }

    public function test_user_cannot_update_permission_group()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('update-permission-group');

        $permissionGroup = PermissionGroup::create([
            'name' => 'Test Permission Group',
        ]);

        $response = $this->actingAs($user)->put(route('admin.permission-groups.update', $permissionGroup->id), [
            'name' => 'Updated Permission Group',
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('permission_groups', [
            'name' => 'Updated Permission Group',
        ]);
    }

    public function test_guest_cannot_update_permission_group()
    {
        $permissionGroup = PermissionGroup::create([
            'name' => 'Test Permission Group',
        ]);

        $response = $this->put(route('admin.permission-groups.update', $permissionGroup->id), [
            'name' => 'Updated Permission Group',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('permission_groups', [
            'name' => 'Updated Permission Group',
        ]);
    }

    public function test_admin_can_delete_permission_group()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('delete-permission-group');

        $permissionGroup = PermissionGroup::create([
            'name' => 'Test Permission Group',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.permission-groups.destroy', $permissionGroup->id));
        $response->assertRedirect(route('admin.permission-groups.index'));
    }

    public function test_user_cannot_delete_permission_group()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('delete-permission-group');

        $permissionGroup = PermissionGroup::create([
            'name' => 'Test Permission Group',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.permission-groups.destroy', $permissionGroup->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('permission_groups', [
            'name' => 'Test Permission Group',
        ]);
    }

    public function test_guest_cannot_delete_permission_group()
    {
        $permissionGroup = PermissionGroup::create([
            'name' => 'Test Permission Group',
        ]);

        $response = $this->delete(route('admin.permission-groups.destroy', $permissionGroup->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('permission_groups', [
            'name' => 'Test Permission Group',
        ]);
    }

    public function test_admin_can_bulk_delete_permission_group()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('bulk-delete-permission-groups');

        $permissionGroup1 = PermissionGroup::create([
            'name' => 'Test Permission Group 1',
        ]);
        $permissionGroup2 = PermissionGroup::create([
            'name' => 'Test Permission Group 2',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.permission-groups.bulkDestroy'), [
            'ids' => [$permissionGroup1->id, $permissionGroup2->id],
        ]);
        $response->assertRedirect(route('admin.permission-groups.index'));
        $this->assertDatabaseMissing('permission_groups', [
            'name' => 'Test Permission Group',
        ]);
    }

    public function test_user_cannot_bulk_delete_permission_group()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('bulk-delete-permission-groups');

        $permissionGroup1 = PermissionGroup::create([
            'name' => 'Test Permission Group 1',
        ]);
        $permissionGroup2 = PermissionGroup::create([
            'name' => 'Test Permission Group 2',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.permission-groups.bulkDestroy'), [
            'ids' => [$permissionGroup1->id, $permissionGroup2->id],
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseHas('permission_groups', [
            'name' => 'Test Permission Group 1',
        ]);
        $this->assertDatabaseHas('permission_groups', [
            'name' => 'Test Permission Group 2',
        ]);
    }

    public function test_guest_cannot_bulk_delete_permission_group()
    {
        $permissionGroup1 = PermissionGroup::create([
            'name' => 'Test Permission Group 1',
        ]);
        $permissionGroup2 = PermissionGroup::create([
            'name' => 'Test Permission Group 2',
        ]);

        $response = $this->delete(route('admin.permission-groups.bulkDestroy'), [
            'ids' => [$permissionGroup1->id, $permissionGroup2->id],
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('permission_groups', [
            'name' => 'Test Permission Group 1',
        ]);
        $this->assertDatabaseHas('permission_groups', [
            'name' => 'Test Permission Group 2',
        ]);
    }
}
