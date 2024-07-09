<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Cart; // Added this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth; // Added this line

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = Cart::with(['cartItems.product.category'])->where("user_id", Auth::user()->id)->first();
        return Inertia::render('Cart/Index', compact('cart'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartRequest $request)
    {
        DB::beginTransaction();

        try {
            $cart = Cart::with('cartItems.product')->findOrFail($request->cart_id);

            $totalPrice = $cart->cartItems->sum(fn ($cartItem) => $cartItem->quantity * $cartItem->product->price);

            $order = Order::create([
                'user_id' => Auth::user()->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
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

            DB::commit();

            return redirect()->route('order.create', ['id' => $order->id]);
        } catch (\Exception $th) {
            DB::rollBack();
            return back()->with([
                'error' => $th->getMessage()
            ]);
        }
    }

    public function addToCart(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        DB::beginTransaction();

        try {
            $cart = Cart::firstOrCreate([
                'user_id' => auth()->user()->id
            ]);

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($cartItem) {
                DB::rollBack();
                return back()->with([
                    'error' => 'Product already exists in cart'
                ]);
            }

            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => 1
            ]);

            DB::commit();

            return back()->with([
                'success' => 'Product added to cart'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'error' => 'An error occurred while adding the product to the cart'
            ]);
        }
    }

    public function removeFromCart(Request $request, $productId)
    {
        DB::beginTransaction();

        try {
            $cart = $request->user()->carts()->firstOrFail();
            $cartItem = $cart->cartItems()->where('product_id', $productId)->firstOrFail();
            $cartItem->delete();

            DB::commit();

            return back()->with([
                'success' => 'Product removed from cart'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'error' => $e->getMessage()
            ]);
        }
    }
}