<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Services\CreateSnapTokenService;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use Mockery;
use Midtrans\Snap;

class CreateSnapTokenServiceTest extends TestCase
{
    public function test_get_snap_token()
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->name = 'John Doe';
        $user->email = 'john@example.com';

        $orderItem1 = Mockery::mock(OrderItem::class)->makePartial();
        $orderItem1->product_id = 1;
        $orderItem1->product = (object) ['name' => 'Product 1'];
        $orderItem1->price = 100;
        $orderItem1->quantity = 2;

        $orderItem2 = Mockery::mock(OrderItem::class)->makePartial();
        $orderItem2->product_id = 2;
        $orderItem2->product = (object) ['name' => 'Product 2'];
        $orderItem2->price = 200;
        $orderItem2->quantity = 1;

        $order = Mockery::mock(Order::class)->makePartial();
        $order->order_code = 'ORDER123';
        $order->user = $user;
        $order->orderItems = collect([$orderItem1, $orderItem2]);

        $service = new CreateSnapTokenService($order);

        // Mock the Snap class
        $snapMock = Mockery::mock('alias:' . Snap::class);
        $snapMock->shouldReceive('getSnapToken')
            ->once()
            ->with(Mockery::on(function ($params) {
                return $params['transaction_details']['order_id'] === 'ORDER123' &&
                    $params['transaction_details']['gross_amount'] === 400 &&
                    count($params['item_details']) === 2;
            }))
            ->andReturn('snap-token-123');

        $snapToken = $service->getSnapToken();

        $this->assertEquals('snap-token-123', $snapToken);
    }
}
