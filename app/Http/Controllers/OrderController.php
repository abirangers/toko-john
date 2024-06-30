<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

        $orders = $query->orderByRaw("FIELD(status, 'completed', 'pending', 'canceled') ASC, created_at DESC")->get();
        
        $totalOrder = $orders->where('status', 'completed')->sum(function ($order) {
            return $order->orderItems->sum('product.price');
        });

        return Inertia::render('Order/Index', compact('orders', 'orderStatusParams', 'totalOrder'));
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
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}