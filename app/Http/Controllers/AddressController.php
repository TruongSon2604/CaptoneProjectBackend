<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\AddressUpdateRequest;
use App\Services\AddressService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * AddressService constructor.
     *
     * @param AddressService $addressService The service used to handle category logic.
     */
    public function __construct(protected AddressService $addressService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $addresses = $this->addressService->getAllWithPagination();
        return response()->json([
            'status' => true,
            'data' => $addresses,
            'message' => "Get Category Successful"
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
    public function store(AddressRequest $request): JsonResponse
    {
        try {
            $addresses = $this->addressService->create($request->validated());
            // dd($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Address created successfully',
                'data' => $addresses
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
            $address = $this->addressService->find($id);
            if (!$address) {
                throw new \Exception("Address not found");
            }

            return response()->json([
                'status' => true,
                'message' => 'Show address successfully',
                'data' => $address
            ], 200);

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
    public function update(AddressUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $updateResult = $this->addressService->update($validatedData, $id);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'Address updated successfully',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Address not found',
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
    public function destroy(string $id): JsonResponse
    {
        try {
            $address = $this->addressService->delete($id);
            if ($address) {
                return response()->json([
                    'status' => true,
                    'data' => $address,
                    'message' => 'Delete Category Successful'
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
    public function getAddressByUser()
    {
        return response()->json([
            'data'=>$this->addressService->getAddressByUser(),
            'status' => true,
            'message' => "Get AddressByUser Successful"
        ]);
    }
}
