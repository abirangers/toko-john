<?php

namespace Tests\Feature\Admin;
use App\Http\Controllers\Admin\ProductCrudController;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Log;

class ProductControllerTest extends AdminTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);

        $controller = new ProductCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_can_access_index_products_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-products');
        
        $response = $this->actingAs($admin)->get(route('admin.products.index'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_index_products_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('find-all-products');
        
        $response = $this->actingAs($user)->get(route('admin.products.index'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_index_products_view()
    {
        $response = $this->get(route('admin.products.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_create_product_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-product');

        $response = $this->actingAs($admin)->get(route('admin.products.create'));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_create_product_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('create-product');

        $response = $this->actingAs($user)->get(route('admin.products.create'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_create_product_view()
    {
        $response = $this->get(route('admin.products.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_product()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('create-product');

        $data = [
            'title' => 'New Product',
            'description' => 'New Product Description',
            'price' => 99.99,
            'stock' => 10,
            'image' => 'image.jpg',
            'category_id' => Category::first()->id,
        ];

        $response = $this->actingAs($admin)->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product created successfully');
        $this->assertDatabaseHas('products', $data);
    }

    public function test_user_cannot_create_product()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('create-product');

        $data = [
            'title' => 'New Product',
            'description' => 'New Product Description',
            'price' => 99.99,
            'stock' => 10,
            'image' => 'image.jpg',
            'category_id' => Category::first()->id,
        ];

        $response = $this->actingAs($user)->post(route('admin.products.store'), $data);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_create_product()
    {
        $response = $this->post(route('admin.products.store'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_show_product_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-product');

        $product = Product::factory()->create();        

        $response = $this->actingAs($admin)->get(route('admin.products.show', $product->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_show_product_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('find-product');

        $product = Product::factory()->create();        

        $response = $this->actingAs($user)->get(route('admin.products.show', $product->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_show_product_view()
    {
        $product = Product::factory()->create();        

        $response = $this->get(route('admin.products.show', $product->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_edit_product_view()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-product');

        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.products.edit', $product->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_edit_product_view()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('update-product');

        $product = Product::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.products.edit', $product->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_edit_product_view()
    {
        $product = Product::factory()->create();
        $response = $this->get(route('admin.products.edit', $product->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_product()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('update-product');

        $product = Product::factory()->create();

        $data = [
            'title' => 'New Product',
            'description' => 'New Product Description',
            'price' => 99.99,
            'stock' => 10,
            'image' => 'image.jpg',
            'category_id' => Category::first()->id,
        ];

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product->id), $data);
        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product updated successfully');
        $this->assertDatabaseHas('products', $data);
    }

    public function test_user_cannot_update_product()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('update-product');

        $product = Product::factory()->create();

        $data = [
            'title' => 'New Product',
            'description' => 'New Product Description',
            'price' => 99.99,
            'stock' => 10,
            'image' => 'image.jpg',
            'category_id' => Category::first()->id,
        ];

        $response = $this->actingAs($user)->put(route('admin.products.update', $product->id), $data);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_update_product()
    {
        $product = Product::factory()->create();
        $response = $this->put(route('admin.products.update', $product->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_delete_product()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('delete-product');

        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.products.destroy', $product->id));
        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product deleted successfully');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_user_cannot_delete_product()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('delete-product');

        $product = Product::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.products.destroy', $product->id));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_delete_product()
    {
        $product = Product::factory()->create();
        $response = $this->delete(route('admin.products.destroy', $product->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_bulk_delete_product()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('bulk-delete-products');

        $products = Product::factory()->count(3)->create();
        $ids = $products->pluck('id')->toArray();

        $response = $this->actingAs($admin)->delete(route('admin.products.bulkDestroy'), ['ids' => $ids]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Bulk delete products successfully');

        foreach ($ids as $id) {
            $this->assertDatabaseMissing('products', ['id' => $id]);
        }
    }

    public function test_user_cannot_bulk_delete_product()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('bulk-delete-products');

        $products = Product::factory()->count(3)->create();
        $ids = $products->pluck('id')->toArray();

        $response = $this->actingAs($user)->delete(route('admin.products.bulkDestroy'), ['ids' => $ids]);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_bulk_delete_product()
    {
        $products = Product::factory()->count(3)->create();
        $ids = $products->pluck('id')->toArray();

        $response = $this->delete(route('admin.products.bulkDestroy'), ['ids' => $ids]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

}