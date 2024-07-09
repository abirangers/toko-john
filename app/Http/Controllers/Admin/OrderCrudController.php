<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\BulkDestroyRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OrderCrudController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'index' => [
                PermissionMiddleware::using('find-all-orders'),
            ],
            'show' => [
                PermissionMiddleware::using('find-order'),
            ],
            'edit' => [
                PermissionMiddleware::using('update-order'),
            ],
            'update' => [
                PermissionMiddleware::using('update-order'),
            ],
            'destroy' => [
                PermissionMiddleware::using('delete-order'),
            ],
            'bulkDestroy' => [
                PermissionMiddleware::using('bulk-delete-orders'),
            ],
        ];
    }

    public function index()
    {
        $orders = Order::with(['orderItems.product', 'user'])->get();

        return Inertia::render('Admin/Order/Index', [
            'orders' => $orders,
        ]);
    }

    public function show(int $id)
    {
        $order = Order::with(['user', 'orderItems.product.category'])->findOrFail($id);

        return Inertia::render('Admin/Order/Show', [
            'order' => $order,
        ]);
    }

    public function edit(int $id)
    {
        $order = Order::with(['orderItems.product', 'user'])->findOrFail($id);

        return Inertia::render('Admin/Order/Manage', [
            'order' => $order,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $order = Order::with(['orderItems.product', 'user'])->findOrFail($id);
        $order->update($request->all());

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order updated successfully');
    }

    public function destroy(int $id)
    {
        Order::findOrFail($id)->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order deleted successfully');
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->validated('ids');
        Order::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Orders deleted successfully');
    }

    public function confirm(int $id)
    {
        $order = Order::with(['orderItems.product'])->findOrFail($id);

        if ($order->status !== 'paid') {
            foreach ($order->orderItems as $item) {
                $item->product->decrement('stock', $item->quantity);
            }
        }

        $order->update(['status' => 'paid']);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order confirmed successfully');
    }
}
