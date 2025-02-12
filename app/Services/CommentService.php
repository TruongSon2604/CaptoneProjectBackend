<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    /**
     * Constructor for initializing the comment repository.
     *
     * @param  \App\Repositories\CommentRepository  $commentRepository  The comment repository instance.
     */
    public function __construct(protected CommentRepository $commentRepository)
    {
        //
    }

    /**
     * Create a new comment.
     *
     * @param  array  $data  The data for creating the new comment.
     * @return \App\Models\Comment  The created comment instance.
     */
    public function create(array $data): Comment
    {
        return $this->commentRepository->create($data);
    }

    /**
     * Update an existing comment.
     *
     * @param  array  $data  The data to update the comment with.
     * @param  int  $id  The ID of the comment to update.
     *
     * @return \App\Models\Comment  The updated comment instance.
     */
    public function update(array $data, int $id): Comment
    {
        return $this->commentRepository->update($data, $id);
    }

    /**
     * Get all comments with pagination.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator  The paginated list of comments.
     */
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return $this->commentRepository->getAllWithPagination();
    }

    /**
     * Delete comment by id
     *
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->commentRepository->delete($id);
    }

    /**
     * Find a comment by its ID.
     *
     * @param  int  $id  The ID of the comment to find.
     * @return mixed  The found comment or null if not found.
     */
    public function find(int $id): mixed
    {
        return $this->commentRepository->find($id);
    }

    /**
     * Update a comment by the user.
     *
     * @param  array  $data  The data for updating the user's comment.
     *
     * @return \App\Models\Comment  The updated comment instance.
     */
    public function UserUpdateComment(array $data): Comment
    {
        return $this->commentRepository->UserUpdateComment($data);
    }

    /**
     * Delete a comment by the user.
     *
     * @param  array  $data  The data for deleting the user's comment.
     * @return mixed  The result of the delete operation.
     */
    public function UserDeleteComment(array $data)
    {
        return $this->commentRepository->UserDeleteComment($data);
    }
}
