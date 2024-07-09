<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;
use App\Mail\PaymentSuccessMail;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function payment($order_code)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = "SB-Mid-server-gtPw11TxOdOmPx_IbLAbC7rD";
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $order = Order::with(['user', 'orderItems.product'])->where('order_code', $order_code)->firstOrFail();

        // Generate a unique order_id by appending a timestamp
        $unique_order_id = $order->order_code . '-' . time();

        $params = array(
            'transaction_details' => array(
                'order_id' => $unique_order_id,
                'gross_amount' => intval($order->total_price), // Ensure gross_amount is an integer
            ),
            'customer_details' => array(
                'name' => $order->user->name,
                'email' => $order->user->email,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return Inertia::render('Payment/Index', compact('order', 'snapToken'));
    }   

    public function paymentSuccess(Request $request, $order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();
        $order->status = 'paid';
        $order->payment_date = $request->transaction_time; // Store the payment date and time
        $order->save();

        // Update product stock
        foreach ($order->orderItems as $orderItem) {
            $product = $orderItem->product;
            $product->stock -= $orderItem->quantity;
            $product->save();
        }

        $paymentDetails = [
            'name' => $order->user->name,
            'order_id' => $order->order_code,
            'total' => $order->orderItems->sum('product.price'),
            'payment_date' => $order->payment_date->format('d F Y H:i:s'), // Include payment date and time in the details
        ];

        // Perform any additional actions after payment success, e.g., sending a confirmation email
        Mail::to($order->user->email)->send(new PaymentSuccessMail($paymentDetails));

        return redirect()->route('order.index')->with('success', 'Payment successful and order status updated.');
    }
}