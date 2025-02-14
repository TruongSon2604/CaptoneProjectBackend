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

}
