<?php

namespace App\Http\Controllers;

use App\Services\CreateSnapTokenService;
use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;
use Midtrans\Config;
use App\Mail\PaymentSuccessMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function payment($order_code)
    {
        $order = Order::with(['user', 'orderItems.product'])->where('order_code', $order_code)->firstOrFail();

        if (!$order) {
            Log::error('Order not found', ['order_code' => $order_code]);
            return redirect()->route('order.index')->with('error', 'Order not found');
        }

        try {
            $createSnapTokenService = new CreateSnapTokenService($order);
            $snapToken = $createSnapTokenService->getSnapToken();

            Log::info('Respon API Midtrans:', ['snapToken' => $snapToken]);
            return Inertia::render('Payment/Index', compact('order', 'snapToken'));
        } catch (\Exception $e) {
            Log::error('Error API Midtrans: ' . $e->getMessage());
            return redirect()->route('order.index')->with('error', 'An error occurred while processing your payment. Please try again later.');
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