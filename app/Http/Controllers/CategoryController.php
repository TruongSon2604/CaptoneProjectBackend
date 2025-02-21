<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Services\CategoryService;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * CategoryController constructor.
     *
     * @param CategoryService $categoryService The service used to handle category logic.
     */
    public function __construct(protected CategoryService $categoryService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAllWithPagination();
        return response()->json([
            'status' => true,
            'data' => $categories,
            'message' => "Get Category Successful"
        ]);
    }

    /**
     * Store a new category in the database.
     *
     * @param \App\Http\Requests\CategoryRequest $request The validated category data.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating success or failure.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->categoryService->create($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
                'data' => $category
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
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->categoryService->find($id);
            if (!$category) {
                throw new \Exception("Category not found");
            }

            return response()->json([
                'status' => true,
                'message' => 'Show category successfully',
                'data' => $category
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update a category in the database.
     *
     * @param \App\Http\Requests\CategoryUpdateRequest $request The validated category data.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating success or failure.
     */
    public function update(CategoryUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $updateResult = $this->categoryService->update($validatedData, $id);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'Category updated successfully',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Category not found',
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
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
            $category = $this->categoryService->delete($id);
            if ($category) {
                return response()->json([
                    'status' => true,
                    'data' => $category,
                    'message' => 'Delete Category Successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Category not found',
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProductByCategory(Request $request): JsonResponse
    {
        try {
            $id = $request->id;
            $products = $this->categoryService->getProductByCategory($id);
            return response()->json([
                'status' => true,
                'data' => $products,
                'message' => "Get Product by Category Successful"
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
