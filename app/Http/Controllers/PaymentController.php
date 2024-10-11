<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;
use Midtrans\Config;
use App\Mail\PaymentSuccessMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function payment($order_code)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $order = Order::with(['user', 'orderItems.product'])->where('order_code', $order_code)->firstOrFail();

        // Generate a unique order_id by appending a timestamp
        $unique_order_id = $order->order_code . '-' . time();

        $params = array(
            'transaction_details' => array(
                'order_id' => $unique_order_id,
                'gross_amount' => intval($order->total_price),
            ),
            'customer_details' => array(
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ),
            'item_details' => $order->orderItems->map(function ($item) {
                return [
                    'id' => $item->product->id,
                    'price' => intval($item->product->price),
                    'quantity' => $item->quantity,
                    'name' => $item->product->title,
                ];
            })->toArray(),
        );

        try {
            \Log::info('Midtrans API Request:', $params);
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            \Log::info('Midtrans API Response:', ['snapToken' => $snapToken]);
            return Inertia::render('Payment/Index', compact('order', 'snapToken'));
        } catch (\Exception $e) {
            \Log::error('Midtrans API Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while processing your payment. Please try again later.');
        }
    }

    public function paymentSuccess(Request $request, $order_code)
    {
        \Log::info('Payment success process started for order: ' . $order_code);
        DB::beginTransaction();

        try {
            $order = Order::where('order_code', $order_code)->lockForUpdate()->firstOrFail();
            $order->status = 'paid';
            $order->save();

            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;
                $oldStock = $product->stock;
                $product->stock -= $orderItem->quantity;
                $product->save();
            }

            $paymentDetails = [
                'name' => $order->user->name,
                'order_id' => $order->order_code,
                'total' => $order->total_price,
                'payment_date' => $order->created_at->format('d F Y H:i:s'),
            ];

            DB::commit();

            Mail::to($order->user->email)->send(new PaymentSuccessMail($paymentDetails));

            return redirect()->route('order.index')->with('success', 'Payment successful and order status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while processing your payment. Please try again later.');
        }
    }
}