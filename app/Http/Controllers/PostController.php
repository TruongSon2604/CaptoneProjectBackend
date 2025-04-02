<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(protected PostService $postService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = $this->postService->getAllWithPagination();
        return response()->json([
            'status' => true,
            'data' => $categories,
            'message' => "Get post Successful"
        ]);
    }

    /**
     * Store a new category in the database.
     *
     * @param \App\Http\Requests\CategoryRequest $request The validated category data.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating success or failure.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $post = $this->postService->create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
                'data' => $post
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
            $category = $this->postService->find($id);
            if (!$category) {
                throw new \Exception("Post not found");
            }

            return response()->json([
                'status' => true,
                'message' => 'Show Post successfully',
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
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validatedData = $request->all();
            $updateResult = $this->postService->update($validatedData, $id);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'post updated successfully',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'post not found',
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
    public function destroy(Request $request): JsonResponse
    {
        try {
            $post = $this->postService->delete($request->ids);
            if ($post) {
                return response()->json([
                    'status' => true,
                    'data' => $post,
                    'message' => 'Delete post Successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'post not found',
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
