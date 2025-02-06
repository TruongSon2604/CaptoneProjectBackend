<?php

namespace App\Contracts;

interface AddressesInterface extends BaseInterface
{
    /**
     * Create a new address record.
     *
     * @param array $data
     *
     * @return \App\Models\Address
     */
    public function create(array $data);

    /**
     * Update an existing address record.
     *
     * @param array $data
     *
     * @return \App\Models\Address
     */
    public function update(array $data, int $id);

    /**
     * Get all address Paginate records .
     *
     * @return mixed
     */
    public function getAllWithPagination();
}
