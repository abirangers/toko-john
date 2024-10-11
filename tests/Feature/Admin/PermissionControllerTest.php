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

        Permission::create([
            'name' => 'test',
            'display_name' => 'test',
            'group_name' => 'test',
            'guard_name' => 'web',
        ]);

        $controller = new PermissionCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_permission_access()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-permissions', 'create-permission', 'update-permission', 'delete-permission', 'bulk-delete-permissions');

        // Test admin can access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.permissions.{$view}", $view === 'show' || $view === 'edit' ? Permission::first()->id : null);
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }

        // Test admin can perform create, update, delete actions
        $response = $this->actingAs($admin)->post(route('admin.permissions.store'), [
            'name' => 'test2',
            'display_name' => 'test2',
            'group_name' => 'test2',
            'guard_name' => 'web',
        ]);
        $response->assertRedirect(route('admin.permissions.index'));
        $response->assertSessionHas('success', 'Permission created successfully');
        $this->assertDatabaseHas('permissions', ['name' => 'test2']);

        $role = Permission::first();
        $response = $this->actingAs($admin)->put(route('admin.permissions.update', $role->id), [
            'name' => 'test3',
            'display_name' => 'test3',
            'group_name' => 'test3',
            'guard_name' => 'web',
        ]);
        $response->assertRedirect(route('admin.permissions.index'));
        $response->assertSessionHas('success', 'Permission updated successfully');
        $this->assertDatabaseHas('permissions', ['name' => 'test3']);

        $response = $this->actingAs($admin)->delete(route('admin.permissions.destroy', $role->id));
        $response->assertRedirect(route('admin.permissions.index'));
        $response->assertSessionHas('success', 'Permission deleted successfully');
        $this->assertDatabaseMissing('permissions', ['name' => 'test3']);
    }

    public function test_user_permission_access()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        // Test user cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.permissions.{$view}", $view === 'show' || $view === 'edit' ? Permission::first()->id : null);
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(403);
        }

        // Test user cannot perform create, update, delete actions
        $response = $this->actingAs($user)->post(route('admin.permissions.store'), [
            'name' => 'test',
            'display_name' => 'test',
            'group_name' => 'test',
            'guard_name' => 'web',
        ]);
        $response->assertStatus(403);

        $role = Permission::first();
        $response = $this->actingAs($user)->put(route('admin.permissions.update', $role->id), [
            'name' => 'test2',
            'display_name' => 'test2',
            'group_name' => 'test2',
            'guard_name' => 'web',
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($user)->delete(route('admin.permissions.destroy', $role->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('permissions', ['name' => 'test']);
    }

    public function test_guest_permission_access()
    {
        // Test guest cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.permissions.{$view}", $view === 'show' || $view === 'edit' ? Permission::first()->id : null);
            $response = $this->get($route);
            $response->assertStatus(302);
            $response->assertRedirect(route('login'));
        }

        // Test guest cannot perform create, update, delete actions
        $response = $this->post(route('admin.permissions.store'), [
            'name' => 'test',
            'display_name' => 'test',
            'group_name' => 'test',
            'guard_name' => 'web',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $role = Permission::first();
        $response = $this->put(route('admin.permissions.update', $role->id), [
            'name' => 'test2',
            'display_name' => 'test2',
            'group_name' => 'test2',
            'guard_name' => 'web',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $response = $this->delete(route('admin.permissions.destroy', $role->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('permissions', ['name' => 'test']);
    }
}
