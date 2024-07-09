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

    public function test_admin_can_access_index_orders_view(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-all-orders');

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('Order');
    }

    public function test_user_cannot_access_index_orders_view(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('find-all-orders');

        $this->actingAs($user)
            ->get(route('admin.orders.index'))
            ->assertStatus(403);
    }

    public function test_guest_cannot_access_index_orders_view(): void
    {
        $this->get(route('admin.orders.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_access_show_order_view(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('find-order');

        $order = Order::factory()->create();

        $orderItem = $order->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order->id))
            ->assertOk()
            ->assertSee($order->order_code)
            ->assertSee($order->user->name)
            ->assertSee($orderItem->product->title)
            ->assertSee($orderItem->quantity)
            ->assertSee($orderItem->price);
    }

    public function test_user_cannot_access_show_order_view(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('find-order');

        $order = Order::factory()->create();

        $order->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $this->actingAs($user)
            ->get(route('admin.orders.show', $order->id))
            ->assertStatus(403);
    }

    public function test_guest_cannot_access_show_order_view(): void
    {
        $order = Order::factory()->create();
        $order->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $this->get(route('admin.orders.show', $order->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_delete_order(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('delete-order');

        $order = Order::factory()->create();

        $orderItem = $order->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.orders.destroy', $order->id))
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success', 'Order deleted successfully')
            ->assertDontSee($order->order_code)
            ->assertDontSee($orderItem->product->title);
    }

    public function test_user_cannot_delete_order(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('delete-order');

        $order = Order::factory()->create();

        $order->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $this->actingAs($user)
            ->delete(route('admin.orders.destroy', $order->id))
            ->assertStatus(403)
            ->assertForbidden();
    }

    public function test_guest_cannot_delete_order(): void
    {
        $order = Order::factory()->create();

        $order->orderItems()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $this->delete(route('admin.orders.destroy', $order->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_bulk_delete_orders(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $admin->givePermissionTo('bulk-delete-orders');

        $order1 = Order::factory()->create();
        $order2 = Order::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.orders.bulkDestroy', ['ids' => [$order1->id, $order2->id]]))
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success', 'Orders deleted successfully')
            ->assertDontSee($order1->order_code)
            ->assertDontSee($order2->order_code);
    }

    public function test_user_cannot_bulk_delete_orders(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $user->givePermissionTo('bulk-delete-orders');

        $order1 = Order::factory()->create();
        $order2 = Order::factory()->create();

        $this->actingAs($user)
            ->delete(route('admin.orders.bulkDestroy', ['ids' => [$order1->id, $order2->id]]))
            ->assertStatus(403)
            ->assertForbidden();
    }

    public function test_guest_cannot_bulk_delete_orders(): void
    {
        $order1 = Order::factory()->create();
        $order2 = Order::factory()->create();

        $this->delete(route('admin.orders.bulkDestroy', ['ids' => [$order1->id, $order2->id]]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }
}
