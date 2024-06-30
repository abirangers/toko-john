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
        $cart = Cart::where("user_id", Auth::user()->id)->first();
        $cartItems = CartItem::with('product.category')->where("cart_id", $cart->id)->get();

        return Inertia::render('Cart/Index', compact('cartItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartRequest $request)
    {
        if (auth()->user()->isSuperAdmin()) {
            return back()->with([
                'error' => 'You are not authorized to perform this action'
            ]);
        }

        $request->validated();

        DB::beginTransaction();

        try {
            $cartItemsCollection = collect($request->cartItems); // Convert array to collection

            $order = Order::create([
                'user_id' => Auth::user()->id,
                'total_price' => $cartItemsCollection->sum(function($cartItem) {
                    $product = Product::find($cartItem['product_id']);
                    return $cartItem['quantity'] * $product->price;
                }),
                'status' => 'pending',
            ]);

            foreach ($request->cartItems as $cartItem) {
                $product = Product::find($cartItem['product_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product_id'],
                    'quantity' => $cartItem['quantity'],
                    'price' => $product->price,
                ]);
            }

            CartItem::where('cart_id', $request->user()->carts[0]->id)->delete();

            DB::commit();

            return back()->with([
                'success' => 'Order created successfully'
            ]);
        } catch (\Exception $th) {
            DB::rollBack();
            return back()->with([
                'error' => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function addToCart(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'productId' => 'required|exists:products,id',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $carts = Cart::firstOrCreate([
                'user_id' => $user->id
            ]);

            $cartItem = CartItem::where('cart_id', $carts->id)->where('product_id', $request->productId)->first();

            if ($cartItem) {
                DB::rollBack();
                return back()->with([
                    'error' => 'Product already exists in cart'
                ]);
            } else {
                $cartItem = CartItem::create([
                    'cart_id' => $carts->id,
                    'product_id' => $request->productId,
                    'quantity' => 1
                ]);
            }

            DB::commit();

            return back()->with([
                'success' => 'Product added to cart'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function removeFromCart(Request $request, $productId)
    {
        DB::beginTransaction();

        try {
            $cartItem = CartItem::where('cart_id', $request->user()->carts[0]->id)
                ->where('product_id', $productId)
                ->firstOrFail();
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