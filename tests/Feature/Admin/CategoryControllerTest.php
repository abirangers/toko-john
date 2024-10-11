<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\CategoryCrudController;
use App\Models\User;
use App\Models\Category;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategoryControllerTest extends AdminTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'test'
        ]);
        $controller = new CategoryCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_category_access()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-categories', 'create-category', 'update-category', 'delete-category', 'bulk-delete-categories');

        // Test admin can access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.categories.{$view}", $view === 'show' || $view === 'edit' ? Category::first()->id : null);
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }

        // Test admin can perform create, update, delete actions
        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'test'
        ]);
        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category created successfully');
        $this->assertDatabaseHas('categories', ['name' => 'test']);

        $role = Category::first();
        $response = $this->actingAs($admin)->put(route('admin.categories.update', $role->id), [
            'name' => 'test2'
        ]);
        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category updated successfully');
        $this->assertDatabaseHas('categories', ['name' => 'test2']);

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $role->id));
        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category deleted successfully');
        $this->assertDatabaseMissing('categories', ['name' => 'test2']);
    }

    public function test_user_category_access()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        // Test user cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.categories.{$view}", $view === 'show' || $view === 'edit' ? Category::first()->id : null);
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(403);
        }

        // Test user cannot perform create, update, delete actions
        $response = $this->actingAs($user)->post(route('admin.categories.store'), [
            'name' => 'test',
        ]);
        $response->assertStatus(403);

        $role = Category::first();
        $response = $this->actingAs($user)->put(route('admin.categories.update', $role->id), [
            'name' => 'test2'
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($user)->delete(route('admin.categories.destroy', $role->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('categories', ['name' => 'test']);
    }

    public function test_guest_category_access()
    {
        // Test guest cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.categories.{$view}", $view === 'show' || $view === 'edit' ? Category::first()->id : null);
            $response = $this->get($route);
            $response->assertStatus(302);
            $response->assertRedirect(route('login'));
        }

        // Test guest cannot perform create, update, delete actions
        $response = $this->post(route('admin.categories.store'), [
            'name' => 'test'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $role = Category::first();
        $response = $this->put(route('admin.categories.update', $role->id), [
            'name' => 'test2'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $response = $this->delete(route('admin.categories.destroy', $role->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('categories', ['name' => 'test']);
    }

}