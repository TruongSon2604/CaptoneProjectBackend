<?php

namespace App\Repositories;

use App\Contracts\CouponInterface;
use App\Models\Coupon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CouponRepository extends BaseRepository implements CouponInterface
{
    public function getModel(): string
    {
        return Coupon::class;
    }

    public function create(array $data): Coupon
    {
        return $this->model->create($data);
    }

    public function update(array $data, int $id): Coupon
    {
        $coupon = $this->model->findOrFail($id);
        $coupon->update($data);
        return $coupon;
    }

    public function getAllWithPagination(): LengthAwarePaginator
    {
        return $this->model->paginate(10);
    }
}
