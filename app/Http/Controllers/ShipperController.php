<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptOrderRequest;
use App\Http\Requests\ShipperLoginRequest;
use App\Http\Requests\ShipperRegisterRequest;
use App\Http\Requests\ShipperUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Shiper;
use App\Services\ShipperService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;

class ShipperController extends Controller
{
    /**
     * ShipperController constructor.
     *
     * @param ShipperService $shipperService The service used to handle category logic.
     */
    public function __construct(protected ShipperService $shipperService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $shippers = $this->shipperService->getAllWithPagination();
        return response()->json([
            'data' => $shippers,
            'status' => true,
            'message' => 'Get Shippers Successful'
        ]);
    }

    /**
     * Register a new shipper.
     *
     * @param ShipperRegisterRequest $request The request object containing the shipper's registration data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(ShipperRegisterRequest $request): JsonResponse
    {
        $this->shipperService->register($request->validated());
        return response()->json([
            'message' => 'Shipper created successfully. Please check your email for verification.',
        ]);
    }

    /**
     * Verify the email address of a shipper.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmail(Request $request)
    {
        $shipper = $this->shipperService->verifyEmail($request->route('id'));

        if ($shipper->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        if ($shipper->markEmailAsVerified()) {
            return response()->json(['message' => 'Email verified successfully']);
        }

        return response()->json(['message' => 'Email verification failed'], 400);
    }


    /**
     * Handle the login request for a shipper.
     *
     * @param ShipperLoginRequest $request The login request containing validated credentials.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the result of the login attempt.
     */
    public function login(ShipperLoginRequest $request)
    {
        $data= $this->shipperService->login($request->validated());

        if($data=="login failed"){
            return response()->json([
                'message' => 'Login Failed.',
                'status'=>false,
            ]);
        }

        if($data=="not been verified"){
            return response()->json(['message' => 'Your email has not been verified. Please verify your email first.'], 403);
        }

        return response()->json([
            'message' => 'Login successful.',
            'status'=>true,
            'email' => $data->email,
            'name' => $data->name,
            'phone' => $data->phone,
        ]);
    }

    /**
     * Display the specified shipper.
     *
     * @param int $id The ID of the shipper to display.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception If the shipper is not found.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $shipper = $this->shipperService->find($id);
            if (!$shipper) {
                throw new \Exception("Shipper not found");
            }

            return response()->json([
                'status' => true,
                'message' => 'Show Shipper successfully',
                'data' => $shipper
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified shipper.
     *
     * @param ShipperUpdateRequest $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function update(ShipperUpdateRequest $request,int $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $updateResult = $this->shipperService->update($validatedData, $id);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'Shipper updated successfully',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Shipper not found',
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
            $shipper = $this->shipperService->delete($id);
            if ($shipper) {
                return response()->json([
                    'status' => true,
                    'data' => $shipper,
                    'message' => 'Delete Shipper Successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Shipper not found',
            ], JsonResponse::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Send a password reset link to the shipper's email.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function sendLinkk(Request $request): JsonResponse
    {
        dd("vao day ne");
        $request->validate(['email' => 'required|email|exists:shippers,email']);

        $status = Password::broker('shippers')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Password reset link sent.'])
            : response()->json(['message' => 'Unable to send password reset link.'], 500);
    }

    /**
     * Reset the shipper's password.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resettPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:shippers,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::broker('shippers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($shipper, $password) {
                $shipper->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password has been reset.'])
            : response()->json(['message' => 'Unable to reset password.'], 500);
    }

    public function acceptOrder(AcceptOrderRequest $request)
    {
        $status=$this->shipperService->acceptOrder($request->validated());

        if($status){
            return response()->json([
                'message' => 'Shipper đã nhận đơn hàng thành công!',
                'status'=>true,
            ]);
        }
        return response()->json([
            'message' => 'Đơn hàng đã được xử lý hoặc không hợp lệ.',
            'status'=>false,
        ]);
    }
}
