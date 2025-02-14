<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Coupon;
use App\Repositories\CouponRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class CouponService
{

    /**
     * CouponService constructor.
     *
     * @param CouponRepository $couponRepository
     */
    public function __construct(protected CouponRepository $couponRepository)
    {

    }

    /**
     * Retrieve all coupons, paginated.
     *
     * @return mixed
     */
    public function getAll(): mixed
    {
        return $this->couponRepository->getAll();
    }

    /**
     * Delete Category by id
     *
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->couponRepository->delete($id);
    }

    /**
     * Create a new Coupon using the provided data.
     *
     * @param array $data
     *
     * @return \App\Models\Coupon The created Coupon instance.
     */
    public function create(array $data): Coupon
    {
        return $this->couponRepository->create(data: $data);
    }

    /**
     * Find Coupon by id
     *
     *@param int $id
     *
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->couponRepository->find($id);
    }

    /**
     * Update an existing Coupon with the provided data.
     *
     * @param array $data
     * @param int $id The ID of the Coupon to update.
     *
     * @return \App\Models\Category|false The updated Coupon instance, or false if not found.
     */
    public function update(array $data, int $id): mixed
    {
        return $this->couponRepository->update($data, $id);
    }

    /**
     * Retrieve all records Coupon pagination.
     *
     * @return mixed
     */
    public function getAllWithPagination(): mixed
    {
        return $this->couponRepository->getAllWithPagination();
    }

    public function getDiscountAmount($couponId=null,$totalAmount): float
    {
        return $this->couponRepository->getDiscountAmount($couponId,$totalAmount);
    }
}
