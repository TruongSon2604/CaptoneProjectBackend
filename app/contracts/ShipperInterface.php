<?php

namespace App\Contracts;

interface ShipperInterface extends BaseInterface
{
    /**
     * Registers a new shipper with the provided data.
     *
     * @param array $data An associative array containing the shipper's details.
     *
     * @return mixed The result of the registration attempt
     */
    public function register(array $data);

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
