<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Http\Requests\UserDeleteCommentRequest;
use App\Http\Requests\UserUpdateCommentRequest;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * CommentService constructor.
     *
     * @param CommentService $commentService The service used to handle category logic.
     */
    public function __construct(protected CommentService $commentService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $comments = $this->commentService->getAllWithPagination();
        return response()->json([
            'status' => true,
            'data' => $comments,
            'message' => "Get comments Successful"
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
     * Store a new comment.
     *
     * @param  \App\Http\Requests\CommentRequest  $request  The validated request data for the comment.
     * @return \Illuminate\Http\JsonResponse  JSON response indicating success or failure.
     */
    public function store(CommentRequest $request): JsonResponse
    {
        try {
            $comment = $this->commentService->create($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Comment created successfully',
                'data' => $comment
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
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $comment = $this->commentService->find($id);
            if (!$comment) {
                throw new \Exception("Comment not found");
            }

            return response()->json([
                'status' => true,
                'message' => 'Show Comment successfully',
                'data' => $comment
            ], status: JsonResponse::HTTP_CREATED);

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
     * Update an existing comment.
     *
     * @param  \App\Http\Requests\CommentUpdateRequest  $request  The validated request data for updating the comment.
     * @param  int  $id  The ID of the comment to be updated.
     *
     * @return \Illuminate\Http\JsonResponse  JSON response indicating success or failure of the update operation.
     */
    public function update(CommentUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $updateResult = $this->commentService->update($validatedData, $id);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'Comment updated successfully',
                ], JsonResponse::HTTP_CREATED);
            }

            return response()->json([
                'status' => false,
                'message' => 'Comment not found',
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
     * Delete an existing comment.
     *
     * @param  string  $id  The ID of the comment to be deleted.
     *
     * @return \Illuminate\Http\JsonResponse  JSON response indicating success or failure of the delete operation.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $comment = $this->commentService->delete($id);
            if ($comment) {
                return response()->json([
                    'status' => true,
                    'data' => $comment,
                    'message' => 'Delete Comment Successful'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Comment not found',
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
     * Update a comment by the user.
     *
     * @param  \App\Http\Requests\UserUpdateCommentRequest  $request  The validated request data for updating the user's comment.
     *
     * @return \Illuminate\Http\JsonResponse  JSON response indicating success or failure of the update operation.
     */
    public function UserUpdateComment(UserUpdateCommentRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $updateResult = $this->commentService->UserUpdateComment($validatedData);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'User update Comment successfully',
                ], JsonResponse::HTTP_OK);
            }

            return response()->json([
                'status' => false,
                'message' => 'Comment not found',
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
     * Delete a comment by the user.
     *
     * @param  \App\Http\Requests\UserDeleteCommentRequest  $request  The validated request data for deleting the user's comment.
     *
     * @return \Illuminate\Http\JsonResponse  JSON response indicating success or failure of the delete operation.
     */
    public function UserDeleteComment(UserDeleteCommentRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $updateResult = $this->commentService->UserDeleteComment($validatedData);
            if ($updateResult) {
                return response()->json([
                    'status' => true,
                    'data' => $updateResult,
                    'message' => 'User delete Comment successfully',
                ], JsonResponse::HTTP_OK);
            }

            return response()->json([
                'status' => false,
                'message' => 'Comment not found',
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
