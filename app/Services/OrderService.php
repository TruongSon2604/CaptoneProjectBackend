<?php

namespace App\Services;

use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\UserCoupon;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
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
            $shipping_fee = 25000;
            $finalAmount = $totalAmount - $discountAmount + $shipping_fee;
            Log::info('Create order zalo3 ' . $finalAmount);
            $dataOrder = [
                'user_id' => $user->id,
                'address_id' => $address_id,
                'coupon_id' => $coupon_id ? $coupon_id : null,
                'order_number' => 'ORD-' . date('YmdHi'),
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => 'pending',
                'shipping_fee' => 25000.00,
            ];

            $order = $this->orderRepository->createOrder($dataOrder);
            Log::info('Create order zalo4 ' . $order);
            // dd($order);
            try {
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
            } catch (Exception $e) {
                Log::info('catch');
                dd($e->getMessage());
            }
            $updateCouponUser = UserCoupon::where('user_id', Auth::id())
                ->where('coupon_id', $coupon_id)
                ->update(['applied_at' => Carbon::now()]);
            Log::info('commit');
            DB::commit();

            return $order->load('address');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getFinalAmount(array $data)
    {
        Log::info("hello2");
        $user = Auth::user();
        $address_id = $data['address_id'];
        $coupon_id = $data['coupon_id']??null;
        $cartItems = $data['cartItems'];
        if (empty($cartItems)) {
            return response()->json(['message' => 'Your select is empty.'], 400);
        }
        $totalAmount = $this->productService->getTotalAmountOrder($cartItems);
        if (!is_numeric($totalAmount)) {
            return response()->json(['message' => 'Invalid total amount.'], 400);
        }

        $discountAmount = $this->couponService->getDiscountAmount($coupon_id, $totalAmount);
        $shipping_fee = 25000;
        $finalAmount = $totalAmount - $discountAmount + $shipping_fee;
        Log::info("finalAmount".$finalAmount.",totalAmount:".$totalAmount.",discountAmount:".$discountAmount);
        return $finalAmount;
    }

    public function createOrderZalo(array $data)
    {
        DB::beginTransaction();
        try {
            // $user = Auth::user();
            $address_id = $data['address_id'];
            $coupon_id = $data['coupon_id']??null;
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
            $shipping_fee = 25000;
            $finalAmount = $totalAmount - $discountAmount + $shipping_fee;
            Log::info('Create order zalo3 ' . $finalAmount);
            $dataOrder = [
                'user_id' => $data['user_id'],
                'address_id' => $address_id,
                'coupon_id' => $coupon_id ? $coupon_id : null,
                'order_number' => 'ORD-' . date('YmdHi'),
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'status' => 'pending',
                'status_payment' => 'paid',
                'transaction_id' => $data['transaction_id'],
                'shipping_fee' => 25000.00,
            ];
            $order = $this->orderRepository->create($dataOrder);
            Log::info('Create order zalo4 ' . $order);
            // dd($order);
            try {
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
            } catch (Exception $e) {
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

    public function getAllOrder()
    {
        return $this->orderRepository->getAllOrder();
    }

    public function getAllOrderOfUser()
    {
        return $this->orderRepository->getAllOrderOfUser();
    }
    public function getOrderDetailOfUser(int $id)
    {
        return $this->orderRepository->getOrderDetailOfUser($id);
    }
}
