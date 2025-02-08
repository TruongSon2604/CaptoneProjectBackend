<?php

namespace App\Contracts;

interface DiscountInterface extends BaseInterface
{
     /**
     * Create a new discount record.
     *
     * @param array $data
     *
     * @return \App\Models\Category
     */
    public function create(array $data);

    /**
     * Update an existing discount record.
     *
     * @param array $data
     *
     * @return \App\Models\Category
     */
    public function update(array $data, int $id);

    /**
     * Get all discount Paginate records .
     *
     * @return mixed
     */
    public function getAllWithPagination();
}
