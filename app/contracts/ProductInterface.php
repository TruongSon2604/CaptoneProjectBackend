<?php

namespace App\Contracts;

interface ProductInterface extends BaseInterface {
     /**
     * Create a new product record.
     *
     * @param array $data
     *
     * @return \App\Models\Product
     */
    public function create(array $data);

    /**
     * Update an existing product record.
     *
     * @param array $data
     *
     * @return \App\Models\Product
     */
    public function update(array $data, int $id);

    /**
     * Get all product Paginate records .
     *
     * @return mixed
     */
    public function getAllWithPagination();

    public function getProductByid(int $id);
}
