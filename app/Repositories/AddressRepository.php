<?php

namespace App\Repositories;

use App\Contracts\AddressesInterface;
use App\Models\Address;
use Illuminate\Pagination\LengthAwarePaginator;

class AddressRepository extends BaseRepository implements AddressesInterface
{
    /**
     * Get the model class name.
     *
     * @return string
     */
    public function getModel(): string
    {
        return Address::class;
    }

    /**
     * Create a new address.
     *
     * @param array $data
     *
     * @return Address
     */
    public function create(array $data): Address
    {
        return Address::create([
            'user_id' => $data['user_id'],
            'district' => $data['district'],
            'commune' => $data['commune'],
            'city' => $data['city'],
            'address_detail' => $data['address_detail'],
            'phone' => $data['phone'],
        ]);
    }

    /**
     * Update an existing address.
     *
     * @param array $data
     * @param int $id
     *
     * @return mixed
     */
    public function update(array $data, int $id): mixed
    {
        $address = $this->find($id);
        if (!$address) {
            return false;
        }

        $address->update([
            'district' => $data['district'],
            'commune' => $data['commune'],
            'city' => $data['city'],
            'address_detail' => $data['address_detail'],
            'phone' => $data['phone'],
        ]);

        return $address;
    }

    /**
     * Get all addresses with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return Address::paginate(Address::ITEM_PER_PAGE);
    }
}
