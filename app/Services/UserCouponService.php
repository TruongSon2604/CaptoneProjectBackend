<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Repositories\UserCouponRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class UserCouponService
{

    /**
     * UserCouponService constructor.
     *
     * @param UserCouponRepository $userCouponRepository
     */
    public function __construct(protected UserCouponRepository $userCouponRepository)
    {

    }

    /**
     * Retrieve all UserCoupon, paginated.
     *
     * @return mixed
     */
    public function getAll(): mixed
    {
        return $this->userCouponRepository->getAll();
    }

    /**
     * Delete UserCoupon by id
     *
     * @return mixed
     */
    public function deleteUserCoupon(array $data): mixed
    {
        return $this->userCouponRepository->deleteUserCoupon($data);
    }

    /**
     * Create a new UserCoupon using the provided data.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return $this->userCouponRepository->create(data: $data);
    }

    /**
     * Find UserCoupon by id
     *
     *@param int $id
     *
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->userCouponRepository->find($id);
    }

    /**
     * Update an existing UserCoupon with the provided data.
     *
     * @param array $data
     * @param int $id The ID of the UserCoupon to update.
     *
     * @return \App\Models\Category|false The updated UserCoupon instance, or false if not found.
     */
    public function update(array $data, int $id): mixed
    {
        return $this->userCouponRepository->update($data, $id);
    }

    /**
     * Retrieve all records UserCoupon pagination.
     *
     * @return mixed
     */
    public function getAllWithPagination(): mixed
    {
        return $this->userCouponRepository->getAllWithPagination();
    }
}
