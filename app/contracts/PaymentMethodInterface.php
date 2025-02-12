<?php

namespace App\Contracts;

interface PaymentMethodInterface extends BaseInterface
{
    /**
     * Create a new PaymentMethod record.
     *
     * @param array $data
     *
     * @return \App\Models\PaymentMethod
     */
    public function create(array $data);

    /**
     * Update an existing PaymentMethod record.
     *
     * @param array $data
     *
     * @return \App\Models\PaymentMethod
     */
    public function update(array $data, int $id);

    /**
     * Get all PaymentMethod Paginate records .
     *
     * @return mixed
     */
    public function getAllWithPagination();

}
