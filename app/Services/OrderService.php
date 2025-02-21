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
            Log::info('Create order zalo1');
            $user = Auth::user();
            $address_id = $data['address_id'];
            $coupon_id = $data['coupon_id'];
            $cartItems = $data['cartItems'];
            Log::info('Create order zalo2');
            if (empty($cartItems)) {
                return response()->json(['message' => 'Your select is empty.'], 400);
            }
            $totalAmount = $this->productService->getTotalAmountOrder($cartItems);
            if (!is_numeric($totalAmount)) {
                return response()->json(['message' => 'Invalid total amount.'], 400);
            }

            $discountAmount = $this->couponService->getDiscountAmount($coupon_id, $totalAmount);

            $finalAmount = $totalAmount - $discountAmount;
            Log::info('Create order zalo3 ' . $finalAmount);
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

            $order = $this->orderRepository->createOrder($dataOrder);
            Log::info('Create order zalo4 ' . $order);
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
                Log::info('catch');
                dd($e->getMessage());
            }
            Log::info('commit');
            DB::commit();

            return $order->load('address');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function createOrderZalo(array $data)
    {
        DB::beginTransaction();
        try {
            // $user = Auth::user();
            $address_id = $data['address_id'];
            $coupon_id = $data['coupon_id'];
            $cartItems = $data['cartItems'];
            Log::info('Create order zalo2');
            if (empty($cartItems)) {
                return response()->json(['message' => 'Your select is empty.'], 400);
            }
            $totalAmount = $this->productService->getTotalAmountOrder($cartItems);
            if (!is_numeric($totalAmount)) {
                return response()->json(['message' => 'Invalid total amount.'], 400);
            }

            $discountAmount = $this->couponService->getDiscountAmount($coupon_id, $totalAmount);

            $finalAmount = $totalAmount - $discountAmount;
            Log::info('Create order zalo3 ' . $finalAmount);
            $dataOrder = [
                'user_id' => $data['user_id'],
                'address_id' => $address_id,
                'coupon_id' => $coupon_id ? $coupon_id : null,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => 'pending',
                'status_payment' => 'paid',
                'transaction_id'=> $data['transaction_id'],
                'shipping_fee' => 10000.00,
            ];
            $order = $this->orderRepository->create($dataOrder);
            Log::info('Create order zalo4 ' . $order);
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
                Log::info('catch');
                dd($e->getMessage());
            }
            Log::info('commit');
            DB::commit();

            return $order->load('address');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }


}
