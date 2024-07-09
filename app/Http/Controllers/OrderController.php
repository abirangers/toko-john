<?php

namespace App\Http\Controllers;

use App\Jobs\SendOrderInvoiceMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Mail\OrderInvoiceMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderStatusParams = request('status');
        $query = Order::with(["orderItems.product", "orderItems.product.category"])->where("user_id", auth()->user()->id);

        if ($orderStatusParams) {
            $query->where('status', $orderStatusParams);
        }

        $orders = $query->orderByRaw("FIELD(status, 'paid', 'pending', 'cancelled') DESC, created_at DESC")->get();

        $totalOrder = $orders->where('status', 'paid')->sum(function ($order) {
            return $order->orderItems->sum('product.price');
        });

        return Inertia::render('Order/Index', compact('orders', 'orderStatusParams', 'totalOrder'));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $order = Order::with(['orderItems.product', 'user'])->findOrFail($id);
        return Inertia::render('Order/OrderInvoice', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $id)
    {
        $order = Order::with(['orderItems.product', 'user'])->find($id);
        return Inertia::render('Order/OrderCheckout', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|exists:users,name',
            'email' => 'required|email|max:255|exists:users,email',
        ]);

        $order = Order::with(['orderItems.product'])->findOrFail($id);

        $orderDetails = [
            'name' => $order->user->name,
            'order_id' => $order->order_code,
            'product' => $order->orderItems->first()->product->title,
            'date' => now()->format('d F Y H:i:s'),
            'due_date' => now()->addHours(1)->format('d F Y H:i:s'),
            'total' => $order->orderItems->sum('product.price'),
        ];

        SendOrderInvoiceMail::dispatch($request->email, $orderDetails);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return back()->with('success', 'Order deleted successfully');
    }
}