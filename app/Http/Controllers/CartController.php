<?php

namespace App\Http\Controllers;

use App\Http\Requests\addMultipleToCartRequest;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    //
    public function __construct(protected CartService $cartService)
    {

    }

    public function getCartItem()
    {
        $cartItem = $this->cartService->getCartItem();

        return response()->json([
            'status' => true,
            'data' => $cartItem,
            'message' => 'Show Cart item of user'
        ]);
    }

    public function addToCart(AddToCartRequest $request)
    {
        try {
            $item = $this->cartService->addToCart($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Add To Cartsuccessfully',
                'data' => $item
            ], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCart(UpdateCartRequest $updateCartRequest)
    {
        try {
            $validatedData = $updateCartRequest->validated();
            $updateResult = $this->cartService->updateCart($validatedData);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'Update Cart successfully',
                ], JsonResponse::HTTP_OK);
            }

            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeFromCart(RemoveFromCartRequest $request)
    {
        try {
            $cart = $this->cartService->removeFromCart($request->validated());
            if ($cart) {
                return response()->json([
                    'status' => true,
                    'data' => $cart,
                    'message' => 'Remove from cart Successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Adddress not found',
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function calculateTotal()
    {
        $calculateTotal = $this->cartService->calculateTotal();
        if ($calculateTotal) {
            return response()->json([
                'status' => true,
                'data' => $calculateTotal,
                'message' => 'Calculate Total From Cart'
            ]);
        }
    }

    public function addMultipleToCart(addMultipleToCartRequest $addMultipleToCartRequest)
    {
        $addMultipleToCartRequest = $this->cartService->addMultipleToCart($addMultipleToCartRequest->validated());
        return response()->json([
            'status'=>true,
            'message'=>'Add Multiple to cart successfull'
        ]);
    }
}
