<?php

namespace App\Repositories;

use App\Contracts\AddressInterface;
use App\Models\Address;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressRepository extends BaseRepository implements AddressInterface
{
    public function getModel()
    {
        return Address::class;
    }

    public function create(array $data): Address
    {
        return Address::create([
            'phone'=>$data['phone'],
            'ward'=>$data['ward'],
            'user_id'=>Auth::id(),
            'address_detail'=>$data['address_detail'],
            'provice'=>$data['provice'],
            'district'=>$data['district'],
        ]);
    }

    public function update(array $data, int $id): Address
    {
        $address = Address::find($id);
        $address->update($data);

        return $address;
    }
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return Address::paginate(10);
    }

    public function getAddressByUser()
    {
        $userId = Auth::user()->id;
        return DB::table('addresses')->where('user_id',$userId)->get();
    }
}
