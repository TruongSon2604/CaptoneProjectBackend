<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusPaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {

    }

    // public function checkOrderPayment(StatusPaymentRequest $statusPaymentRequest)
    // {
    //     $message= $this->paymentService->checkAndSavePayment($statusPaymentRequest->validated());
    //     return response()->json([
    //         'message' => $message
    //     ]);
    // }

    public function UpdatePaymentOrder(UpdatePaymentRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $updateResult = $this->paymentService->UpdatePaymentOrder($validatedData);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'Comment updated successfully',
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
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
