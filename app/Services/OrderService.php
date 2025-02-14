<?php

namespace App\Services;

use App\Models\OrderDetail;
use App\Models\Product;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(protected OrderRepository $orderRepository, protected ProductService $productService, protected CouponService $couponService)
    {

    }

    public function createOrder(array $data)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $address_id = $data['address_id'];
            $coupon_id = $data['coupon_id'];
            $cartItems = $data['cartItems'];

            if (empty($cartItems)) {
                return response()->json(['message' => 'Your select is empty.'], 400);
            }
            $totalAmount = $this->productService->getTotalAmountOrder($cartItems);
            if (!is_numeric($totalAmount)) {
                return response()->json(['message' => 'Invalid total amount.'], 400);
            }

            $discountAmount = $this->couponService->getDiscountAmount($coupon_id, $totalAmount);

            $finalAmount = $totalAmount - $discountAmount;

            $dataOrder = [
                'user_id' => $user->id,
                'address_id' => $address_id,
                'coupon_id' => $coupon_id ? $coupon_id : null,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => 'pending',
                'shipping_fee' => 10000.00,
            ];

            $order = $this->orderRepository->create($dataOrder);
            // dd($order);
            try
            {
                foreach ($cartItems as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    OrderDetail::create([
                        'orders_id' => $order->id,
                        'products_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $product->discounted_price,
                    ]);

                    $product->stock_quantity -= $item['quantity'];
                    $product->save();
                }
            }
            catch(Exception $e)
            {
                dd($e->getMessage());
            }
            DB::commit();
            return $order->load('address');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }


}
