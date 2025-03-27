<?php

namespace App\Repositories;

use App\Contracts\UserCouponInterface;
use App\Models\UserCoupon;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserCouponRepository extends BaseRepository implements UserCouponInterface
{
    public function getModel(): string
    {
        return UserCoupon::class;
    }

    public function create(array $data): mixed
    {

        $userCoupon = UserCoupon::where('user_id', Auth::user()->id)
            ->where('coupon_id', $data['coupon_id'])
            ->first();
        Log::info("---------");
        Log::info($userCoupon);
        if ($userCoupon)
            return false;

        return $this->model->create([
            'user_id' => Auth::user()->id,
            'coupon_id' => $data['coupon_id']
        ]);
    }

    public function update(array $data, int $id): UserCoupon
    {
        $coupon = $this->model::findOrFail($id);
        $coupon->update($data);
        return $coupon;
    }

    public function getAllWithPagination(): LengthAwarePaginator
    {
        return $this->model->paginate(UserCoupon::ITEM_PER_PAGE);
    }

    public function deleteUserCoupon(array $data): mixed
    {
        $user_id = Auth::user()->id;
        $data = $this->getModel()::where('user_id', $user_id)
            ->where('coupon_id', $data['coupon_id'])
            ->first();
        if (!$data)
            return false;
        $data->delete();
        return $data;
    }

    public function getUserWithCoupon()
    {
        $data2 = DB::table('user_coupons')
            ->join('coupons', 'user_coupons.coupon_id', '=', 'coupons.id')
            ->where('user_coupons.user_id', Auth::id())
            ->where('coupons.end_date', '>', Carbon::now())
            ->whereNull('user_coupons.applied_at')
            ->get();
        return $data2;
    }

    public function getAllUserWithCoupon()
    {
        $data2 = DB::table('user_coupons')
            ->join('coupons', 'user_coupons.coupon_id', '=', 'coupons.id')
            ->selectRaw('
            user_coupons.id as user_coupon_id,
            user_coupons.user_id,
            user_coupons.coupon_id,
            user_coupons.applied_at,
            coupons.code,
            coupons.start_date,
            coupons.end_date,
            coupons.discount_type,
            coupons.discount_value
        ')  // Remove the extra comma after coupons.discount_value
            ->where('user_coupons.user_id', Auth::id())
            ->get();

        return $data2;
    }
}
