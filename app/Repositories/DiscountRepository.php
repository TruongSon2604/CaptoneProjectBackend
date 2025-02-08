<?php

namespace App\Repositories;

use App\Contracts\DiscountInterface;
use App\Models\Discount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DiscountRepository extends BaseRepository implements DiscountInterface
{
    /**
     * Get the model class name.
     *
     * @return string The model class name.
     */
    public function getModel(): string
    {
        return Discount::class;
    }

    /**
     * Create a new discount record.
     *
     * @param array $data The data for creating a new discount.
     *
     * @return Discount The created discount instance.
     */
    public function create(array $data): Discount
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing discount record.
     *
     * @param array $data The data for updating the discount.
     * @param int $id The ID of the discount to update.
     *
     * @return Discount The updated discount instance.
     */
    public function update(array $data, int $id): Discount
    {
        $coupon = $this->model->findOrFail($id);
        $coupon->update($data);
        return $coupon;
    }

    /**
     * Retrieve all discounts with pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator Paginated list of discounts.
     */
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return $this->model->paginate(Discount::ITEM_PER_PAGE);
    }
}
