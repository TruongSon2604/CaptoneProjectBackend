<?php

namespace App\Services;

use App\Repositories\ShipperRepository;
use App\Models\Shiper;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class ShipperService
{
    /**
     * ShipperRepository constructor.
     *
     * @param ShipperRepository $shipperRepository
     */
    public function __construct(protected ShipperRepository $shipperRepository)
    {

    }

    /**
     * Retrieve all categories, paginated.
     *
     * @return mixed
     */
    public function getAll(): mixed
    {
        return $this->shipperRepository->getAll();
    }

    /**
     * Delete Shipper by id
     *
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->shipperRepository->delete($id);
    }

    /**
     * Create a new Shiper with the provided data.
     *
     * @param array $data
     */
    public function register(array $data): void
    {
        $shipper= $this->shipperRepository->register($data);
        event(new Registered($shipper));
    }

    /**
     * Find Shipper by id
     *
     *@param int $id
     *
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->shipperRepository->find($id);
    }

    /**
     * Update an existing Shipper with the provided data.
     *
     * @param array $data
     * @param int $id
     *
     * @return \App\Models\Shipper|false
     */
    public function update(array $data, int $id): mixed
    {
        return $this->shipperRepository->update($data, $id);
    }

    public function verifyEmail(int $data)
    {
        $shipper = $this->shipperRepository->verifyEmail($data);
        return $shipper;
    }

    /**
     * Retrieve all records Product pagination.
     *
     * @return mixed
     */
    public function getAllWithPagination(): mixed
    {
        return $this->shipperRepository->getAllWithPagination();
    }

    public function login(array $data): mixed
    {
        $shipper= $this->shipperRepository->getShipper($data);
        if (!$shipper || !Hash::check($data['password'], $shipper->password)) {
            return "login failed";
        }

        if (!$shipper->hasVerifiedEmail()) {
            return "not been verified";
            // return response()->json(['message' => 'Your email has not been verified. Please verify your email first.'], 403);
        }
        return $shipper;
    }
}
