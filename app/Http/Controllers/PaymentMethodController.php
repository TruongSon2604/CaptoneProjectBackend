<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentMethodRequest;
use App\Services\PaymentMethodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentMethodController extends Controller
{
    //
    /**
     * PaymentMethodController constructor.
     *
     * @param PaymentMethodService $paymentMethodService The service used to handle category logic.
     */
    public function __construct(protected PaymentMethodService $paymentMethodService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $paymentMethods = $this->paymentMethodService->getAllWithPagination();
        return response()->json([
            'status' => true,
            'data' => $paymentMethods,
            'message' => "Get payment Methods Successful"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentMethodRequest $request): JsonResponse
    {
        try {
            $paymentMethod = $this->paymentMethodService->create($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Payment Method created successfully',
                'data' => $paymentMethod
            ], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $paymentMethod = $this->paymentMethodService->find($id);
            if (!$paymentMethod) {
                throw new \Exception("Payment Method not found");
            }

            return response()->json([
                'status' => true,
                'message' => 'Show Payment Method successfully',
                'data' => $paymentMethod
            ], JsonResponse::HTTP_CREATED);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentMethodRequest $request, int $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $updateResult = $this->paymentMethodService->update($validatedData, $id);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'Payment Method updated successfully',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Payment Method not found',
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $address = $this->paymentMethodService->delete($id);
            if ($address) {
                return response()->json([
                    'status' => true,
                    'data' => $address,
                    'message' => 'Delete Payment method Successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Payment method not found',
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
