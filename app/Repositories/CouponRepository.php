<?php

namespace App\Repositories;

use App\Contracts\CouponInterface;
use App\Models\Coupon;
use Carbon\Carbon;
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

    public function getDiscountAmount($couponId = null, $totalAmount):float
    {
        // dd($couponId,$totalAmount);
        if ($couponId) {
            $coupon = $this->model::findOrFail($couponId);

            $couponEndDate = Carbon::parse($coupon->end_date);
            $now = Carbon::now();

            if (!$coupon || $coupon->is_active != 1 || $now->lt($couponEndDate)) {
                // return response()->json(data: ['message' => 'Invalid or expired coupon code.']);
                return 0;
            }
            if ($coupon->discount_type === 'percentage') {
                $discountAmount = ($totalAmount * $coupon->discount_value) / 100;
            } else {
                $discountAmount = $coupon->discount_value;
            }
            return $discountAmount;
        }
        return 0;
    }
}
