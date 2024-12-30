<?php

namespace App\Contracts;

use App\Models\Status;

interface StatusInterface extends BaseInterface
{
    /**
     * Create a new book record.
     *
     * @param array $data
     *
     * @return Status
     */
    public function create(array $data);

    /**
     * Update an existing book record.
     *
     * @param array $data
     * @param int  $id
     */
    public function update(array $data, int $id);

    /**
     * Get all Category Paginate records .
     *
     * @return mixed
     */
    public function getAllWithPagination();
}
