<?php

namespace App\Services;

use App\Models\Discount;
use App\Repositories\DiscountRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class DiscountService
{
    /**
     * DiscountService constructor.
     *
     * @param DiscountRepository $discountRepository
     */
    public function __construct(protected DiscountRepository $discountRepository)
    {

    }

    /**
     * Retrieve all Discount.
     *
     * @return mixed
     */
    public function getAll(): mixed
    {
        return $this->discountRepository->getAll();
    }

    /**
     * Delete Discount by id
     *
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->discountRepository->delete($id);
    }

    /**
     * Create a new Discount using the provided data.
     *
     * @param array $data
     *
     * @return \App\Models\Discount The created Discount instance.
     */
    public function create(array $data): Discount
    {
        return $this->discountRepository->create(data: $data);
    }

    /**
     * Find Discount by id
     *
     *@param int $id
     *
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->discountRepository->find($id);
    }

    /**
     * Update an existing Discount with the provided data.
     *
     * @param array $data
     * @param int $id The ID of the Discount to update.
     *
     * @return \App\Models\Category|false The updated Discount instance, or false if not found.
     */
    public function update(array $data, int $id): mixed
    {
        return $this->discountRepository->update($data, $id);
    }

    /**
     * Retrieve all records Discount pagination.
     *
     * @return mixed
     */
    public function getAllWithPagination(): mixed
    {
        return $this->discountRepository->getAllWithPagination();
    }
}
