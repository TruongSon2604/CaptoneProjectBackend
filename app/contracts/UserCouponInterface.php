<?php

namespace App\Contracts;

interface UserCouponInterface extends BaseInterface
{
    /**
     * Create a new coupon record.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing coupon record.
     *
     * @param array $data
     *
     * @return \App\Models\Category
     */
    public function update(array $data, int $id);

    /**
     * Get all Coupon Paginate records .
     *
     * @return mixed
     */
    public function getAllWithPagination();

    public function deleteUserCoupon(array $data);

    public function getUserWithCoupon();

}
