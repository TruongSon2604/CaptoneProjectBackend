<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCouponRequest;
use App\Http\Requests\UserCouponUpdateRequest;
use App\Services\UserCouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UserCouponController extends Controller
{
    /**
     * UserCouponController constructor.
     *
     * @param UserCouponService $userCouponService The service used to handle Coupon logic.
     */
    public function __construct(protected UserCouponService $userCouponService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $coupons = $this->userCouponService->getAllWithPagination();
        return response()->json([
            'status' => true,
            'data' => $coupons,
            'message' => "Get User Coupon Successful"
        ]);
    }

    /**
     * Store a new user coupon in the database.
     *
     * @param \App\Http\Requests\UserCouponRequest $request The validated category data.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating success or failure.
     */
    public function store(UserCouponRequest $request): JsonResponse
    {
        try {
            $coupon = $this->userCouponService->create($request->validated());
            if($coupon==false)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already applied this coupon.',
                ]);
            }
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
            $userCoupon = $this->userCouponService->find($id);
            if (!$userCoupon) {
                throw new \Exception("user Coupon not found");
            }

            return response()->json([
                'status' => true,
                'message' => 'Show coupon successfully',
                'data' => $userCoupon
            ], JsonResponse::HTTP_OK);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UserCouponUpdateRequest $request , int $id): JsonResponse
    {
        try {
            // $validatedData = $request->validated();
            $updateResult = $this->userCouponService->update($request->validated(), $id);
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
     * Delete UserCoupon by id
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function deleteUserCoupon(UserCouponRequest $request): JsonResponse
    {
        try {
            $usercoupon = $this->userCouponService->deleteUserCoupon($request->validated());
            if ($usercoupon) {
                return response()->json([
                    'status' => true,
                    'data' => $usercoupon,
                    'message' => 'Delete User Coupon Successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'User Coupon not found',
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteUC(UserCouponRequest $request): JsonResponse
    {
        try {
            $usercoupon = $this->userCouponService->deleteUserCoupon($request->all());
            if ($usercoupon) {
                return response()->json([
                    'status' => true,
                    'data' => $usercoupon,
                    'message' => 'Delete User Coupon Successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'User Coupon not found',
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
