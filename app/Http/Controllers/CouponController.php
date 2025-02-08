<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Http\Requests\CouponUpdateRequest;
use App\Services\CouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    /**
     * CouponController constructor.
     *
     * @param CouponService $couponService The service used to handle Coupon logic.
     */
    public function __construct(protected CouponService $couponService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $coupons = $this->couponService->getAllWithPagination();
        return response()->json([
            'status' => true,
            'data' => $coupons,
            'message' => "Get coupons Successful"
        ]);
    }

    /**
     * Store a new coupon in the database.
     *
     * @param \App\Http\Requests\CouponRequest $request The validated category data.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating success or failure.
     */
    public function store(CouponRequest $request): JsonResponse
    {
        try {
            $coupon = $this->couponService->create($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Coupon created successfully',
                'data' => $coupon
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
            $category = $this->couponService->find($id);
            if (!$category) {
                throw new \Exception("Coupon not found");
            }

            return response()->json([
                'status' => true,
                'message' => 'Show coupon successfully',
                'data' => $category
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
     * @param \App\Http\Requests\CouponUpdateRequest $request The validated category data.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating success or failure.
     */
    public function update(CouponUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $updateResult = $this->couponService->update($validatedData, $id);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'Coupon updated successfully',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Coupon not found',
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
     * Delete category by id
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $category = $this->couponService->delete($id);
            if ($category) {
                return response()->json([
                    'status' => true,
                    'data' => $category,
                    'message' => 'Delete Coupon Successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Coupon not found',
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
