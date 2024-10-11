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
        $cart = Cart::with(['cartItems.product.category'])->where("user_id", Auth::user()->id)->firstOrFail();
        return Inertia::render('Cart/Index', compact('cart'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
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

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $cart = $request->user()->carts()->firstOrFail();
            $cartItem = $cart->cartItems()->where('product_id', $request->product_id)->firstOrFail();
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