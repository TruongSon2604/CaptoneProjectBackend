<?php

namespace App\Repositories;

use App\Contracts\AddressInterface;
use App\Models\Address;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class AddressRepository extends BaseRepository implements AddressInterface
{
    public function getModel()
    {
        return Address::class;
    }

    public function create(array $data): Address
    {
        return Address::create($data);
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

    public function getAddressByUser(): Collection
    {
        $userId = Auth::user()->id;
        return Address::where('user_id', $userId)->get();
    }
}
