<?php

namespace App\Repositories;

use App\Contracts\UserCouponInterface;
use App\Models\UserCoupon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserCouponRepository extends BaseRepository implements UserCouponInterface
{
    public function getModel(): string
    {
        return UserCoupon::class;
    }

    public function create(array $data): mixed
    {
        $userCoupon = UserCoupon::where('user_id', $data['user_id'])
                            ->where('coupon_id', $data['coupon_id'])
                            ->first();
        if($userCoupon) return false;

        return $this->model->create($data);
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

    public function deleteUserCoupon(array $data):mixed
    {
        $data = $this->getModel()::where('user_id', $data['user_id'])
        ->where('coupon_id', $data['coupon_id'])
        ->first();;
        if(!$data) return false;
        $data->delete();
        return $data;
    }
}
