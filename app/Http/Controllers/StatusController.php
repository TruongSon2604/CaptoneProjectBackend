<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Services\StatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusController extends Controller
{

    /**
     * CategoryController constructor.
     *
     * @param StatusService $statusService The service used to handle category logic.
     */
    public function __construct(protected StatusService $statusService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $statuses=$this->statusService->getAllWithPagination();
        return response()->json([
            'status' => true,
            'data' => $statuses,
            'message' => "Get Status Successful"
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
    public function store(StatusRequest $request): JsonResponse
    {
        try {
            $status = $this->statusService->create($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Status created successfully',
                'data' => $status
            ], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
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
            $status = $this->statusService->find($id);
            if (!$status) {
                throw new \Exception("Status not found");
            }

            return response()->json([
                'status' => true,
                'message' => 'Show Status successfully',
                'data' => $status
            ], 200);

        } catch (\Exception $e) {

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
    public function update(UpdateStatusRequest $request, string $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $updateResult = $this->statusService->update($validatedData, $id);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'Status updated successfully',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Status not found',
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
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
            $status = $this->statusService->delete($id);
            if ($status) {
                return response()->json([
                    'status' => true,
                    'data' => $status,
                    'message' => 'Delete Category Successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'status not found',
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
