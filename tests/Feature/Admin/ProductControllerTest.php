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

        Product::create([
            'title' => 'New Product',
            'description' => 'New Product Description',
            'price' => 99.99,
            'stock' => 10,
            'image' => 'image.jpg',
            'category_id' => Category::first()->id,
        ]);

        $controller = new ProductCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_product_access()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-products', 'create-product', 'update-product', 'delete-product', 'bulk-delete-products');

        // Test admin can access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.products.{$view}", $view === 'show' || $view === 'edit' ? Product::first()->id : null);
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }

        // Test admin can perform create, update, delete actions
        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'title' => 'test2',
            'description' => 'test2',
            'price' => 99.99,
            'stock' => 10,
            'image' => 'image.jpg',
            'category_id' => Category::first()->id,
        ]);
        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product created successfully');
        $this->assertDatabaseHas('products', ['title' => 'test2']);

        $product = Product::first();
        $response = $this->actingAs($admin)->put(route('admin.products.update', $product->id), [
            'title' => 'test3',
            'description' => 'test3',
            'price' => 99.99,
            'stock' => 10,
            'image' => 'image.jpg',
            'category_id' => Category::first()->id,
        ]);
        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product updated successfully');
        $this->assertDatabaseHas('products', ['title' => 'test3']);

        $response = $this->actingAs($admin)->delete(route('admin.products.destroy', $product->id));
        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product deleted successfully');
        $this->assertDatabaseMissing('products', ['title' => 'test3']);
    }

    public function test_user_role_access()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        // Test user cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
                $route = route("admin.products.{$view}", $view === 'show' || $view === 'edit' ? Product::first()->id : null);
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(403);
        }

        // Test user cannot perform create, update, delete actions
        $response = $this->actingAs($user)->post(route('admin.products.store'), [
            'title' => 'test2',
            'description' => 'test2',
            'price' => 99.99,
            'stock' => 10,
            'image' => 'image.jpg',
            'category_id' => Category::first()->id,
        ]);
        $response->assertStatus(403);

        $product = Product::first();
        $response = $this->actingAs($user)->put(route('admin.products.update', $product->id), [
            'title' => 'test3',
            'description' => 'test3',
            'price' => 99.99,
            'stock' => 10,
            'image' => 'image.jpg',
            'category_id' => Category::first()->id,
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($user)->delete(route('admin.products.destroy', $product->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('products', ['title' => 'New Product']);
    }

    public function test_guest_role_access()
    {
        // Test guest cannot access index, create, show, edit views
        $views = ['index', 'create', 'show', 'edit'];
        foreach ($views as $view) {
            $route = route("admin.products.{$view}", $view === 'show' || $view === 'edit' ? Product::first()->id : null);
            $response = $this->get($route);
            $response->assertStatus(302);
            $response->assertRedirect(route('login'));
        }

        // Test guest cannot perform create, update, delete actions
        $response = $this->post(route('admin.products.store'), [
            'title' => 'test2',
            'description' => 'test2',
            'price' => 99.99,
            'stock' => 10,
            'image' => 'image.jpg',
            'category_id' => Category::first()->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $product = Product::first();
        $response = $this->put(route('admin.products.update', $product->id), [
            'title' => 'test3',
            'description' => 'test3',
            'price' => 99.99,
            'stock' => 10,
            'image' => 'image.jpg',
            'category_id' => Category::first()->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        $response = $this->delete(route('admin.products.destroy', $product->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('products', ['title' => 'New Product']);
    }
}