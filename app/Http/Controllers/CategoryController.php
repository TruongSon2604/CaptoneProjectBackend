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
        $categories = $this->categoryService->getAll();
        return response()->json([
            'status' => true,
            'data' => $categories,
            'message' => "Get Category Successful"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->categoryService->create($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
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
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, int $id): JsonResponse
    {

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
        ], 404);
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
        ], 404);
    }
}
