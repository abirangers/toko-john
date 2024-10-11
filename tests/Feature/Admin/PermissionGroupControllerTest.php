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

        PermissionGroup::create([
            'name' => 'test',
        ]);

        $controller = new PermissionGroupCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }


    public function test_admin_permission_group_access()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-permission-groups', 'create-permission-group', 'update-permission-group', 'delete-permission-group', 'bulk-delete-permission-groups');

        // Test admin can access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.permission-groups.{$view}", $view === 'show' || $view === 'edit' ? PermissionGroup::first()->id : null);
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }

        // Test admin can perform create, update, delete actions
        $response = $this->actingAs($admin)->post(route('admin.permission-groups.store'), [
            'name' => 'test2',
        ]);
        $response->assertRedirect(route('admin.permission-groups.index'));
        $response->assertSessionHas('success', 'Permission group created successfully');
        $this->assertDatabaseHas('permission_groups', ['name' => 'test2']);

        $permissionGroup = PermissionGroup::first();
        $response = $this->actingAs($admin)->put(route('admin.permission-groups.update', $permissionGroup->id), [
            'name' => 'test3',
        ]);
        $response->assertRedirect(route('admin.permission-groups.index'));
        $response->assertSessionHas('success', 'Permission group updated successfully');
        $this->assertDatabaseHas('permission_groups', ['name' => 'test3']);

        $response = $this->actingAs($admin)->delete(route('admin.permission-groups.destroy', $permissionGroup->id));
        $response->assertRedirect(route('admin.permission-groups.index'));
        $response->assertSessionHas('success', 'Permission group deleted successfully');
        $this->assertDatabaseMissing('permission_groups', ['name' => 'test3']);
    }

    public function test_user_permission_access()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        // Test user cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.permission-groups.{$view}", $view === 'show' || $view === 'edit' ? PermissionGroup::first()->id : null);
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(403);
        }

        // Test user cannot perform create, update, delete actions
        $response = $this->actingAs($user)->post(route('admin.permission-groups.store'), [
            'name' => 'test',
        ]);
        $response->assertStatus(403);

        $permissionGroup = PermissionGroup::first();
        $response = $this->actingAs($user)->put(route('admin.permission-groups.update', $permissionGroup->id), [
            'name' => 'test2',
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($user)->delete(route('admin.permission-groups.destroy', $permissionGroup->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('permission_groups', ['name' => 'test']);
    }

    public function test_guest_permission_access()
    {
        // Test guest cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.permission-groups.{$view}", $view === 'show' || $view === 'edit' ? PermissionGroup::first()->id : null);
            $response = $this->get($route);
            $response->assertStatus(302);
            $response->assertRedirect(route('login'));
        }

        // Test guest cannot perform create, update, delete actions
        $response = $this->post(route('admin.permission-groups.store'), [
            'name' => 'test',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $permissionGroup = PermissionGroup::first();
        $response = $this->put(route('admin.permission-groups.update', $permissionGroup->id), [
            'name' => 'test2',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $response = $this->delete(route('admin.permission-groups.destroy', $permissionGroup->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('permission_groups', ['name' => 'test']);
    }
}
