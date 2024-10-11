<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Mail\OrderInvoiceMail;
use App\Jobs\SendOrderInvoiceMail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Auth;
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
        $order = Order::with(['orderItems.product.category', 'user'])->findOrFail($id);
        return Inertia::render('Order/OrderInvoice', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $cart = Cart::with(['cartItems.product', 'user'])->where("user_id", Auth::user()->id)->firstOrFail();

        return Inertia::render('Order/OrderCheckout', compact('cart'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        $validatedData = $request->validated();
        $user = Auth::user();

        if ($validatedData['name'] !== $user->name || $validatedData['email'] !== $user->email) {
            return back()->withErrors(['message' => 'Name or email does not match the logged-in user.']);
        }

        DB::beginTransaction();

        try {
            $cart = Cart::with(['cartItems.product'])->findOrFail($validatedData['cart_id']);
            $totalPrice = $cart->cartItems->sum(function ($cartItem) {
                return $cartItem->quantity * $cartItem->product->price;
            });

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'province_id' => $validatedData['province_id'],
                'province_name' => Province::find($validatedData['province_id'])->name,
                'regency_id' => $validatedData['regency_id'],
                'regency_name' => Regency::find($validatedData['regency_id'])->name,
                'district_id' => $validatedData['district_id'],
                'district_name' => District::find($validatedData['district_id'])->name,
                'village_id' => $validatedData['village_id'],
                'village_name' => Village::find($validatedData['village_id'])->name,
                'address' => $validatedData['address'],
            ]);

            foreach ($cart->cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
            }

            $cart->cartItems()->delete();

            $orderDetails = [
                'name' => $order->user->name,
                'order_id' => $order->order_code,
                'product' => $order->orderItems->first()->product->title,
                'date' => now()->format('d F Y H:i:s'),
                'due_date' => now()->addHours(1)->format('d F Y H:i:s'),
                'total' => $order->orderItems->sum('product.price'),
            ];

            SendOrderInvoiceMail::dispatch($validatedData['email'], $orderDetails);

            DB::commit();

            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['error' => $e->getMessage()]);
        }
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