<?php

namespace App\Repositories;

use App\Contracts\CouponInterface;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        return $this->model->paginate(50);
    }

    public function getAllWithPagination2()
    {
        if (Auth::check()) {
            $data = DB::table('coupons')
                ->leftJoin('user_coupons', function ($join) {
                    $join->on('coupons.id', '=', 'user_coupons.coupon_id')
                        ->where('user_coupons.user_id', Auth::user()->id);
                })
                ->select(
                    'coupons.*',
                    DB::raw('
            CASE
                WHEN user_coupons.coupon_id IS NOT NULL THEN true
                ELSE false
            END as status
        ')
                )
                ->get();

            return $data;
        } else {
            // Nếu người dùng chưa đăng nhập, có thể trả về một thông báo lỗi hoặc một giá trị mặc định
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    public function getDiscountAmount($couponId = null, $totalAmount): float
    {
        // dd($couponId,$totalAmount);
        if ($couponId) {
            $coupon = $this->model::findOrFail($couponId);

            $couponEndDate = Carbon::parse($coupon->end_date);
            $now = Carbon::now();

            if (!$coupon) {
                Log::info('Khong vao 1');
                // return response()->json(data: ['message' => 'Invalid or expired coupon code.']);
                return 0;
            }
            if ($coupon->is_active != 1) {
                Log::info('Khong vao 2');
                // return response()->json(data: ['message' => 'Invalid or expired coupon code.']);
                return 0;
            }
            if ($coupon->discount_type === 'percentage') {
                Log::info('percentage');
                $discountAmount = ($totalAmount * $coupon->discount_value) / 100;
            } else {
                Log::info('discount_value' . $coupon->discount_value);
                $discountAmount = $coupon->discount_value;
            }
            return $discountAmount;
        }
        return 0;
    }

    public function deleteMoreCoupon(array|int $ids): mixed
    {
        $ids = is_array($ids) ? $ids : [$ids];
        $coupons = $this->model::whereIn('id', $ids)->get();

        if ($coupons->isEmpty()) {
            return false;
        }

        foreach ($coupons as $coupon) {
            $coupon->delete();
        }
        return true;
    }
}
