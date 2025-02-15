<?php

namespace App\Repositories;

use App\Contracts\OrderInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderRepository extends BaseRepository implements OrderInterface
{
    public function getModel(): string
    {
        return Order::class;
    }

    public function create(array $data)
    {
        try {
            $order = $this->model::create([
                'user_id' => $data['user_id'],
                'order_number' => $data['order_number'],
                'total_amount' => $data['total_amount'],
                'discount_amount' => $data['discount_amount'],
                'final_amount' => $data['final_amount'],
                'status' => 'pending',
                'status_payment' => 'paid',
                'shipping_fee' => $data['shipping_fee'],
                'address_id' => $data['address_id'],
                'coupon_id' => $data['coupon_id'],
                'transaction_id'=> $data['transaction_id'],
            ]);
            return $order;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function updateOrderStatus(array $data)
    {
        $order = Order::findOrFail($data['orderId']);
        $order->status = $data['status'];
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Order status updated successfully',
            'order' => $order,
        ]);
    }
}
