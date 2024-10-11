<?php

namespace Tests\Feature\Admin;

use Database\Seeders\CategorySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Database\Seeders\OrderSeeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Admin\OrderCrudController;
use Database\Seeders\ProductSeeder;

class OrderControllerTest extends AdminTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);

        $controller = new OrderCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_order_access() {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-orders', 'create-order', 'update-order', 'delete-order', 'bulk-delete-orders');

        $order = Order::factory()->create();

        $order->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $views = ['index', 'show'];
        foreach ($views as $view) {
            $route = route("admin.orders.{$view}", $view === 'show' ? Order::first()->id : null);
            $this->actingAs($admin)->get($route)->assertOk();
        }

        $response = $this->actingAs($admin)->delete(route('admin.orders.destroy', $order->id));
        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success', 'Order deleted successfully');
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);


        $order2 = Order::factory()->create();

        $order2->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.orders.bulkDestroy', ['ids' => [$order->id, $order2->id]]));
        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success', 'Orders deleted successfully');
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('orders', ['id' => $order2->id]);
    }

    public function test_user_order_access()
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $order = Order::factory()->create();

        $order->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $views = ['index', 'show'];
        foreach ($views as $view) {
            $route = route("admin.orders.{$view}", $view === 'show' ? Order::first()->id : null);
            $this->actingAs($user)->get($route)->assertStatus(403);
        }

        $response = $this->actingAs($user)->delete(route('admin.orders.destroy', $order->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('orders', ['id' => $order->id]);


        $order2 = Order::factory()->create();

        $order2->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $response = $this->actingAs($user)->delete(route('admin.orders.bulkDestroy', ['ids' => [$order->id, $order2->id]]));
        $response->assertStatus(403);
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
        $this->assertDatabaseHas('orders', ['id' => $order2->id]);
    }

    public function test_guest_order_access()
    {
        $order = Order::factory()->create();
        $order->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $views = ['index', 'show'];
        foreach ($views as $view) {
            $route = route("admin.orders.{$view}", $view === 'show' ? Order::first()->id : null);
            $this->get($route)->assertStatus(302)->assertRedirect(route('login'));
        }

        $response = $this->delete(route('admin.orders.destroy', $order->id));
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseHas('orders', ['id' => $order->id]);


        $order2 = Order::factory()->create();

        $order2->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $response = $this->delete(route('admin.orders.bulkDestroy', ['ids' => [$order->id, $order2->id]]));
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
        $this->assertDatabaseHas('orders', ['id' => $order2->id]);
    }
}
