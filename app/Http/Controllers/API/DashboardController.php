<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;

class DashboardController extends Controller
{
    public function getOrdersPerMonth(Request $request)
    {
        // Determine the year based on the selected option
        $year = $request->input('year', Carbon::now()->year);

        // Get orders per month within the selected year
        $ordersPerMonth = Order::whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($order) {
                return [
                    'month' => Carbon::create()->month($order->month)->format('F'),
                    'orders' => $order->total,
                ];
            });

        return response()->json([
            'data' => $ordersPerMonth,
        ]);
    }
}