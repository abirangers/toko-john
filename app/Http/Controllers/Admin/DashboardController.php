<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $totalUser = User::count();
        $totalProduct = Product::count();
        $totalCategory = Category::count();
        $totalOrder = Order::count();

        $selectedTimeRange = $request->input('timeRange', 'past 12 months');

        // Get orders per month
        $ordersPerMonth = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month') // Ensure the months are ordered
            ->get()
            ->map(function ($order) {
                return [
                    'month' => Carbon::create()->month($order->month)->format('F'),
                    'total' => $order->total,
                ];
            });

        return Inertia::render('Dashboard', [
            'totalUser' => $totalUser,
            'totalProduct' => $totalProduct,
            'totalCategory' => $totalCategory,
            'totalOrder' => $totalOrder,
            'ordersPerMonth' => $ordersPerMonth,
            'selectedTimeRange' => $selectedTimeRange,
        ]);
    }
}