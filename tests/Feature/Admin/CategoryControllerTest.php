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

        $controller = new CategoryCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_can_access_index_categories_view(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-categories');

        $response = $this->actingAs($admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_index_categories_view(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('admin.categories.index'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_index_categories_view(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_create_category_view(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-category');

        $response = $this->actingAs($admin)->get(route('admin.categories.create'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_create_category_view(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('admin.categories.create'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_create_category_view(): void
    {
        $response = $this->get(route('admin.categories.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-category');

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'New Category',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category created successfully');
        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
    }

    public function test_user_cannot_create_category(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->post(route('admin.categories.store'), [
            'name' => 'New Category',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('categories', ['name' => 'New Category']);
    }

    public function test_guest_cannot_create_category(): void
    {
        $response = $this->post(route('admin.categories.store'), [
            'name' => 'New Category',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_show_category_view(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-category');

        $category = Category::create([
            'id' => 1,
            'name' => 'Bed',
            'slug' => 'bed'
        ]);

        $response = $this->actingAs($admin)->get(route('admin.categories.show', $category->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_show_category_view(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $category = Category::create([
            'id' => 1,
            'name' => 'Bed',
            'slug' => 'bed'
        ]);

        $response = $this->actingAs($user)->get(route('admin.categories.show', $category->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_show_category_view(): void
    {
        $category = Category::create([
            'id' => 1,
            'name' => 'Bed',
            'slug' => 'bed'
        ]);

        $response = $this->get(route('admin.categories.show', $category->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_edit_category_view(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-category');

        $category = Category::create(
            [
                'id' => 1,
                'name' => 'Bed',
                'slug' => 'bed'
            ]
        );

        $response = $this->actingAs($admin)->get(route('admin.categories.edit', $category->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_edit_category_view(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $category = Category::create(
            [
                'id' => 1,
                'name' => 'Bed',
                'slug' => 'bed'
            ]
        );

        $response = $this->actingAs($user)->get(route('admin.categories.edit', $category->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_edit_category_view(): void
    {
        $category = Category::create(
            [
                'id' => 1,
                'name' => 'Bed',
                'slug' => 'bed'
            ]
        );

        $response = $this->get(route('admin.categories.edit', $category->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_category(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-category');

        $category = Category::create(
            [
                'id' => 1,
                'name' => 'Bed',
                'slug' => 'bed'
            ]
        );

        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category->id), [
            'name' => 'Updated Category',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category updated successfully');
        $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
    }

    public function test_user_cannot_update_category(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $category = Category::create(
            [
                'id' => 1,
                'name' => 'Bed',
                'slug' => 'bed'
            ]
        );

        $response = $this->actingAs($user)->put(route('admin.categories.update', $category->id), [
            'name' => 'Updated Category',
        ]);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_update_category(): void
    {
        $category = Category::create(
            [
                'id' => 1,
                'name' => 'Bed',
                'slug' => 'bed'
            ]
        );

        $response = $this->put(route('admin.categories.update', $category->id), [
            'name' => 'Updated Category',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_delete_category(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('delete-category');

        $category = Category::create(
            [
                'id' => 1,
                'name' => 'Bed',
                'slug' => 'bed'
            ]
        );

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category->id));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category deleted successfully');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_user_cannot_delete_category(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('delete-category');

        $category = Category::create(
            [
                'id' => 1,
                'name' => 'Bed',
                'slug' => 'bed'
            ]
        );

        $response = $this->actingAs($user)->delete(route('admin.categories.destroy', $category->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_delete_category(): void
    {
        $category = Category::create(
            [
                'id' => 1,
                'name' => 'Bed',
                'slug' => 'bed'
            ]
        );

        $response = $this->delete(route('admin.categories.destroy', $category->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_bulk_delete_categories(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('bulk-delete-categories');

        Category::insert([
            [
                'id' => 1,
                'name' => 'Furniture',
                'slug' => 'furniture'
            ],
            [
                'id' => 2,
                'name' => 'Bed',
                'slug' => 'bed'
            ],
            [
                'id' => 3,
                'name' => 'Chair',
                'slug' => 'chair'
            ]
        ]);
        $ids = Category::pluck('id')->toArray();

        $response = $this->from(route('admin.categories.index'))->actingAs($admin)->delete(route('admin.categories.bulkDestroy'), [
            'ids' => $ids
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Bulk categories deleted successfully');
        foreach ($ids as $id) {
            $this->assertDatabaseMissing('categories', ['id' => $id]);
        }
    }

    public function test_user_cannot_bulk_delete_categories(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        Category::insert([
            [
                'id' => 1,
                'name' => 'Furniture',
                'slug' => 'furniture'
            ],
            [
                'id' => 2,
                'name' => 'Bed',
                'slug' => 'bed'
            ],
            [
                'id' => 3,
                'name' => 'Chair',
                'slug' => 'chair'
            ]
        ]);
        $ids = Category::pluck('id')->toArray();

        $response = $this->actingAs($user)->delete(route('admin.categories.bulkDestroy'), [
            'ids' => $ids
        ]);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_bulk_delete_categories(): void
    {
        Category::insert([
            [
                'id' => 1,
                'name' => 'Furniture',
                'slug' => 'furniture'
            ],
            [
                'id' => 2,
                'name' => 'Bed',
                'slug' => 'bed'
            ],
            [
                'id' => 3,
                'name' => 'Chair',
                'slug' => 'chair'
            ]
        ]);
        $ids = Category::pluck('id')->toArray();

        $response = $this->delete(route('admin.categories.bulkDestroy'), [
            'ids' => $ids
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

}