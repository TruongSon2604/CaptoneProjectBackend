<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\Request;

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
    public function createOrder(OrderRequest $orderRequest)
    {
        $order=$this->orderService->createOrder($orderRequest->validated());
        if($order)
        {
            return response()->json([
                'message' => 'Order placed successfully.',
                'order' => $order,
            ], 201);
        }
        else{
            return response()->json([
                'message' => 'An error occurred while placing the order.',
            ], 500);
        }
    }

    public function getAllOrderOfUser()
    {
        $order=$this->orderService->getAllOrderOfUser();
        if($order)
        {
            return response()->json([
                'message' => 'Get order of user successfully.',
                'order' => $order,
            ], 201);
        }
        else{
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
        return response()->json([
            'status' => true,
            'data' => $order,
            'message' => "Order Cancelled Successfully"
        ]);
    }
   
}
