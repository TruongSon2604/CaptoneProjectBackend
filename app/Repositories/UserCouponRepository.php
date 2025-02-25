<?php

namespace App\Repositories;

use App\Contracts\UserCouponInterface;
use App\Models\UserCoupon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

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
        $data = $this->getModel()::with('coupon')
            ->where('user_id', Auth::id())
            ->whereNull('applied_at')
            ->get();
        return $data;
    }
}
