<?php

namespace App\Services;

use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class CreateSnapTokenService extends Midtrans
{
    protected $order;

    public function __construct($order)
    {
        parent::__construct();

        $this->order = $order;
    }

    public function getSnapToken()
    {
        $itemDetails = $this->getItemsDetails();
        $grossAmount = $this->calculateGrossAmount($itemDetails);

        $params = [
            'transaction_details' => [
                'order_id' => $this->order->order_code,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $this->order->user->name,
                'email' => $this->order->user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Kesalahan saat membuat Snap Token: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function getItemsDetails()
    {
        $itemsDetails = [];

        foreach ($this->order->orderItems as $item) {
            $itemsDetails[] = [
                'id' => $item->product_id,
                'name' => $item->product->name ?? 'Produk Tanpa Nama',
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
            ];
        }

        return $itemsDetails;
    }

    protected function calculateGrossAmount($itemDetails)
    {
        return array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $itemDetails));
    }
}
