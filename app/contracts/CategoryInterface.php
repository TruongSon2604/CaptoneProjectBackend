<?php

namespace App\Contracts;

interface CategoryInterface extends BaseInterface
{
    /**
     * Create a new book record.
     *
     * @param array $data
     *
     * @return \App\Models\Category
     */
    public function create(array $data);

    /**
     * Update an existing book record.
     *
     * @param array $data
     *
     * @return \App\Models\Category
     */
    public function update(array $data, int $id);

    /**
     * Get all Category Paginate records .
     *
     * @return mixed
     */
    public function getAllWithPagination();

    public function getProductByCategory($id);

}
