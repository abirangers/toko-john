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

        Role::create([
            'name' => 'test',
            'display_name' => 'test',
        ]);

        $controller = new RoleCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_role_access()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-roles', 'create-role', 'update-role', 'delete-role', 'bulk-delete-roles');

        // Test admin can access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.roles.{$view}", $view === 'show' || $view === 'edit' ? Role::first()->id : null);
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }

        // Test admin can perform create, update, delete actions
        $response = $this->actingAs($admin)->post(route('admin.roles.store'), [
            'name' => 'test2',
            'display_name' => 'test2',
        ]);
        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success', 'Role created successfully');
        $this->assertDatabaseHas('roles', ['name' => 'test2']);

        $role = Role::first();
        $response = $this->actingAs($admin)->put(route('admin.roles.update', $role->id), [
            'name' => 'test3',
            'display_name' => 'test3',
        ]);
        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success', 'Role updated successfully');
        $this->assertDatabaseHas('roles', ['name' => 'test3']);

        $response = $this->actingAs($admin)->delete(route('admin.roles.destroy', $role->id));
        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success', 'Role deleted successfully');
        $this->assertDatabaseMissing('roles', ['name' => 'test3']);
    }

    public function test_user_role_access()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        // Test user cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.roles.{$view}", $view === 'show' || $view === 'edit' ? Role::first()->id : null);
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(403);
        }

        // Test user cannot perform create, update, delete actions
        $response = $this->actingAs($user)->post(route('admin.roles.store'), [
            'name' => 'test2',
            'display_name' => 'test2',
        ]);
        $response->assertStatus(403);

        $role = Role::first();
        $response = $this->actingAs($user)->put(route('admin.roles.update', $role->id), [
            'name' => 'test3',
            'display_name' => 'test3',
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($user)->delete(route('admin.roles.destroy', $role->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('roles', ['name' => 'test']);
    }

    public function test_guest_role_access()
    {
        // Test guest cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.roles.{$view}", $view === 'show' || $view === 'edit' ? Role::first()->id : null);
            $response = $this->get($route);
            $response->assertStatus(302);
            $response->assertRedirect(route('login'));
        }

        // Test guest cannot perform create, update, delete actions
        $response = $this->post(route('admin.roles.store'), [
            'name' => 'test2',
            'display_name' => 'test2',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $role = Role::first();
        $response = $this->put(route('admin.roles.update', $role->id), [
            'name' => 'test3',
            'display_name' => 'test3',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $response = $this->delete(route('admin.roles.destroy', $role->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('roles', ['name' => 'test']);
    }
}

