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

        $selectedYear = $request->input('year', Carbon::now()->year);

        // Get orders per month for the selected year
        $ordersPerMonth = Order::whereYear('created_at', $selectedYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
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
            'selectedYear' => $selectedYear,
        ]);
    }
}