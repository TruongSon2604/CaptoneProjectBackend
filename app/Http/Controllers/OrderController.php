<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {

    }
    public function index()
    {
        $orders = $this->orderService->getAllOrder();
        return response()->json([
            'status' => true,
            'data' => $orders,
            'message' => "Get Order Successful"
        ]);
    }
    // public function createOrder(OrderRequest $orderRequest)
    // {
    //     $order = $this->orderService->createOrder($orderRequest->validated());
    //     if ($order) {
    //         return response()->json([
    //             'message' => 'Order placed successfully.',
    //             'order' => $order,
    //         ], 201);
    //     } else {
    //         return response()->json([
    //             'message' => 'An error occurred while placing the order.',
    //         ], 500);
    //     }
    // }
    public function createOrder(OrderRequest $orderRequest)
    {
        try {
            $order = $this->orderService->createOrder($orderRequest->validated());

            if ($order) {
                return response()->json([
                    'message' => 'Order placed successfully.',
                    'order' => $order,
                ], 201);
            } else {
                // Log lỗi khi không có order được tạo
                Log::error('Order creation failed', [
                    'data' => $orderRequest->validated(),
                    'message' => 'An error occurred while placing the order.',
                ]);

                return response()->json([
                    'message' => 'An error occurred while placing the order.',
                ], 500);
            }
        } catch (\Throwable $e) {
            // Log chi tiết lỗi nếu có exception
            Log::error('Error while placing order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $orderRequest->validated(),
            ]);

            return response()->json([
                'message' => 'Internal Server Error',
            ], 500);
        }
    }

    public function getAllOrderOfUser()
    {
        $order = $this->orderService->getAllOrderOfUser();
        if ($order) {
            return response()->json([
                'message' => 'Get order of user successfully.',
                'order' => $order,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Order not found',
            ], 500);
        }
    }

    public function getOrderDetailOfUser(Request $request)
    {
        $orders = $this->orderService->getOrderDetailOfUser($request->id);
        return response()->json([
            'status' => true,
            'data' => $orders,
            'message' => "Get Order Details of User Successful"
        ]);
    }
    public function updateOrderStatus(Request $request)
    {
        $order = $this->orderService->updateOrderStatus($request->all());
        return response()->json([
            'status' => true,
            'data' => $order,
            'message' => "update Order Status Successful"
        ]);
    }
    public function cancelOrder(Request $request)
    {
        $order = $this->orderService->cancelOrder($request->all());
        if ($order == 0) {
            return response()->json([
                'message' => 'Cannot cancel a completed order',
                'status' => true,
                'data' => 0
            ], 400);
        } else if ($order == 1) {
            return response()->json([
                'message' => 'Order canceled and stock updated successfully',
                'status' => true,
                'data' => 1
            ]);
        }

        return response()->json([
            'message' => 'Failed to cancel order',
            'status' => false,
            'data' => 2
        ], 500);

    }

    public function getOrderDashBoard()
    {
        $order = $this->orderService->getOrderDashBoard();
        return response()->json([
            'status' => true,
            'data' => $order,
        ]);
    }
    public function getTotal()
    {
        $order = $this->orderService->getTotal();
        return response()->json([
            'status' => true,
            'data' => $order,
        ]);
    }

    public function getRevenueByMonth()
    {
        $order = $this->orderService->getRevenueByMonth();
        return response()->json([
            'status' => true,
            'data' => $order,
        ]);
    }
    public function getOrderByMonth()
    {
        $order = $this->orderService->getOrderByMonth();
        return response()->json([
            'status' => true,
            'data' => $order,
        ]);
    }

    public function getDetailProductSoldByMonth(string $month)
    {
        $order = $this->orderService->getDetailProductSoldByMonth($month);
        return response()->json([
            'status' => true,
            'data' => $order,
        ]);
    }
}
