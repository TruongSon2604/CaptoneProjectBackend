<?php

namespace App\Repositories;

use App\Contracts\AddressInterface;
use App\Models\Address;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
}
