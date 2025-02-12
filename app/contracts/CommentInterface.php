<?php

namespace App\Contracts;

interface CommentInterface extends BaseInterface
{
    /**
     * Create a new Comment record.
     *
     * @param array $data
     *
     * @return \App\Models\Comment
     */
    public function create(array $data);

    /**
     * Update an existing Comment record.
     *
     * @param array $data
     *
     * @return \App\Models\Comment
     */
    public function update(array $data, int $id);

    /**
     * Get all Comment Paginate records .
     *
     * @return mixed
     */
    public function getAllWithPagination();

    /**
     * User Update comment .
     *
     * @param array $data
     *
     * @return \App\Models\Comment
     */
    public function UserUpdateComment(array $data);

    /**
     * User Delete comment .
     *
     * @param array $data
     *
     * @return \App\Models\Comment
     */
    public function UserDeleteComment(array $data);


}
