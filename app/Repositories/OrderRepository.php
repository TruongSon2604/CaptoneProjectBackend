<?php

namespace App\Repositories;

use App\Contracts\OrderInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository extends BaseRepository implements OrderInterface
{
    public function getModel(): string
    {
        return Order::class;
    }
    public function createOrder(array $data)
    {
        try {
            $order = $this->model::create([
                'user_id' => $data['user_id'],
                'address_id' => $data['address_id'],
                'coupon_id' => $data['coupon_id'],
                'order_number' => $data['order_number'],
                'total_amount' => $data['total_amount'],
                'discount_amount' => $data['discount_amount'],
                'final_amount' => $data['final_amount'],
                'status' => 'pending',
                'shipping_fee' => $data['shipping_fee'],
            ]);
            return $order;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
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
                'transaction_id' => $data['transaction_id'],
            ]);
            return $order;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function updateOrderStatus(array $data)
    {
        Log::info('Dữ liệu orderId:', ['orderId' => $data['orderId']]);
        $order = Order::findOrFail($data['orderId']);
        $order->status = $data['status'];
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Order status updated successfully',
            'order' => $order,
        ]);
    }

    public function getAllOrder()
    {
        $ordersUserWithAddress = DB::table('orders')
            ->Join('users', 'orders.user_id', '=', 'users.id')
            ->Join('addresses', 'addresses.id', '=', 'orders.address_id')
            ->selectRaw('
            orders.id,
            orders.created_at,
            orders.order_number,
            orders.discount_amount,
            orders.final_amount,
            orders.status,
            orders.status_payment,
             orders.total_amount,
            orders.shipping_fee,
            users.name,
            users.image,
             addresses.phone
            ')
            ->get();
        return $ordersUserWithAddress;
    }

    public function getAllOrderOfUser()
    {
        $ordersUserWithAddress = Order::with('user')->where('user_id', Auth::id())->get();
        return $ordersUserWithAddress;
    }

    public function getOrderDetailOfUser(int $id)
    {
        $orderDetails = DB::table('order_details')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.orders_id')
            ->leftJoin('products', 'products.id', '=', 'order_details.products_id')
            ->leftJoin('discounts', 'discounts.product_id', '=', 'products.id')
            ->leftJoin('users', 'users.id', '=', 'orders.user_id')
            ->leftJoin('addresses', 'addresses.id', '=', 'orders.address_id')
            ->selectRaw('
            users.name as user_name,
            users.email,
            products.id,
            products.name,
            order_details.quantity,
            orders.total_amount,
            orders.final_amount,
            orders.shipping_fee,
            orders.id as order_id,
            addresses.district,
            addresses.ward,
            addresses.provice,
            addresses.address_detail,
            addresses.phone,
            products.image,
            products.description,
            products.price as original_price,
            COALESCE(discounts.percent_discount, 0) as discount_percent,
            ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price,
            ROUND(order_details.quantity * products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as sub_total
        ')->where('orders.id', $id)->get();

        Log::info($orderDetails);

        return $orderDetails;
    }

    public function cancelOrder(array $data)
    {
        $order = Order::findOrFail($data['id']);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $order->status = 'canceled';
        $order->canceled_at = now();
        $order->cancellation_reason = $data['cancellation_reason'];
        $order->save();

        return $order;
    }
}
