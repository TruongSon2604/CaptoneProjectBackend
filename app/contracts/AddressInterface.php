<?php

namespace App\Contracts;

interface AddressInterface extends BaseInterface
{
    /**
     * Create a new address record.
     *
     * @param array $data
     *
     * @return \App\Models\Category
     */
    public function create(array $data);

    /**
     * Update an existing address record.
     *
     * @param array $data
     *
     * @return \App\Models\Category
     */
    public function update(array $data, int $id);

    /**
     * Get all address Paginate records .
     *
     * @return mixed
     */
    public function getAllWithPagination();
}
