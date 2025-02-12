<?php

namespace App\Services;

use App\Models\Address;
use App\Repositories\AddressRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AddressService
{
    public function __construct(protected AddressRepository $addressRepository)
    {
        //
    }
    public function create(array $data): Address
    {
        return $this->addressRepository->create($data);
    }
    public function update(array $data, int $id): Address
    {
        return $this->addressRepository->update($data, $id);
    }
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return $this->addressRepository->getAllWithPagination();
    }

    /**
     * Delete Address by id
     *
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->addressRepository->delete($id);
    }

    public function find(int $id): mixed
    {
        return $this->addressRepository->find($id);
    }
    public function getAddressByUser(): Collection
    {
        return $this->addressRepository->getAddressByUser();
    }
}
