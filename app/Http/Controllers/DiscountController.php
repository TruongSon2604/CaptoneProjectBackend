<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscountRequest;
use App\Http\Requests\DiscountUpdateRequest;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    /**
     * DiscountController constructor.
     *
     * @param DiscountService $discountService The service used to handle Discount logic.
     */
    public function __construct(protected DiscountService $discountService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $coupons = $this->discountService->getAllWithPagination();
        return response()->json([
            'status' => true,
            'data' => $coupons,
            'message' => "Get coupons Successful"
        ]);
    }

    /**
     * Store a new Discount in the database.
     *
     * @param \App\Http\Requests\DiscountRequest $request The validated category data.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating success or failure.
     */
    public function store(DiscountRequest $request): JsonResponse
    {
        try {
            $discount = $this->discountService->create($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Discount created successfully',
                'data' => $discount
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
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $discount = $this->discountService->find($id);
            if (!$discount) {
                throw new \Exception("Discount not found");
            }

            return response()->json([
                'status' => true,
                'message' => 'Show Discount successfully',
                'data' => $discount
            ], JsonResponse::HTTP_OK);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update a CouponUpdateRequest in the database.
     *
     * @param \App\Http\Requests\DiscountUpdateRequest $request The validated category data.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating success or failure.
     */
    public function update(DiscountUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $updateResult = $this->discountService->update($validatedData, $id);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'Discount updated successfully',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Discount not found',
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
     * Delete Discount by id
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $discount = $this->discountService->delete($id);
            if ($discount) {
                return response()->json([
                    'status' => true,
                    'data' => $discount,
                    'message' => 'Delete Discount Successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Discount not found',
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
