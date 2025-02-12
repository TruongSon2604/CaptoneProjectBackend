<?php

namespace App\Repositories;

use App\Contracts\AddressInterface;
use App\Contracts\CommentInterface;
use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CommentRepository extends BaseRepository implements CommentInterface
{
    /**
     * Get the model associated with the service.
     *
     * @return string  The fully qualified class name of the model.
     */
    public function getModel(): string
    {
        return Comment::class;
    }

    /**
     * Create a new comment.
     *
     * @param  array  $data  The validated data for the new comment.
     *
     * @return \App\Models\Comment  The created comment instance.
     */
    public function create(array $data): Comment
    {
        return Comment::create([
            'user_id' => Auth::user()->id,
            'product_id' => $data['product_id'],
            'content' => $data['content'],
            'rating' => $data['rating']
        ]);
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
        $address = Comment::find($id);
        $address->update($data);

        return $address;
    }

    /**
     * Get all comments with pagination.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator  The paginated list of comments.
     */
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return Comment::paginate(Comment::ITEM_PER_PAGE);
    }

    /**
     * Update a comment by the user.
     *
     * @param  array  $data  The data for updating the user's comment.
     *
     * @return \App\Models\Comment  The updated comment instance.
     *
     * @throws \Exception  If the comment does not exist or doesn't belong to the user.
     */
    public function UserUpdateComment(array $data):Comment
    {
        $comment = $this->model::where('user_id', Auth::user()->id)
            ->where('product_id', $data['product_id'])->first();

            if (!$comment) {
                throw new \Exception("Comment not found or doesn't belong to the user.");
            }
            $comment->update([
                'content' => $data['content'],
                'rating' => $data['rating'],
            ]);

            return $comment;
    }

    /**
     * Delete a comment by the user.
     *
     * @param  array  $data  The data for deleting the user's comment.
     * 
     * @return \App\Models\Comment  The deleted comment instance.
     */
    public function UserDeleteComment(array $data):Comment
    {
        $comment = $this->model::where('user_id', Auth::user()->id)
        ->where('product_id', $data['product_id'])->first();
        $comment->delete();
        return $comment;
    }
}
