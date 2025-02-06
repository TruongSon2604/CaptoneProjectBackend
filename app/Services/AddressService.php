<?php

namespace App\Services;

use App\Models\Address;
use App\Repositories\AddressRepository;

class AddressService
{
    /**
     * AddressService constructor.
     *
     * @param AddressRepository $addressRepository
     */
    public function __construct(protected AddressRepository $addressRepository)
    {
        //
    }

    /**
     * Get all addresses.
     *
     * @return mixed
     */
    public function getAll():mixed
    {
        return $this->addressRepository->getAll();
    }

    /**
     * Get all addresses with pagination.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function getAllWithPagination(): mixed
    {
        return $this->addressRepository->getAllWithPagination();
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
        return $this->addressRepository->create($data);
    }

    /**
     * Update an existing address.
     *
     * @param array $data
     * @param int $id
     *
     * @return mixed
     */
    public function update(array $data, int $id): Address
    {
        return $this->addressRepository->update($data, $id);
    }

    /**
     * Find an address by ID.
     *
     * @param int $id
     * 
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->addressRepository->find($id);
    }

    /**
     * Delete an address by ID.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->addressRepository->delete($id);
    }
}
