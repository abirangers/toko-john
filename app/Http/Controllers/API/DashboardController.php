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
        // Determine the time range based on the selected option
        $timeRange = $request->input('timeRange', 'past 12 months');
        $monthsBack = match ($timeRange) {
            'past 3 months' => 3,
            'past 6 months' => 6,
            default => 12,
        };

        // Get orders per month within the selected time range
        $ordersPerMonth = Order::where('created_at', '>=', Carbon::now()->subMonths($monthsBack))
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