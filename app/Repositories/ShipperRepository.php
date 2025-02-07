<?php

namespace App\Repositories;

use App\Contracts\ShipperInterface;
use App\Models\Shiper;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class ShipperRepository extends BaseRepository implements ShipperInterface
{
    /**
     * Get the model class name.
     *
     * @return string
     */
    public function getModel(): string
    {
        return Shiper::class;
    }

    /**
     * Register a new shipper.
     *
     * @param array $data
     *
     * @return Shiper
     */
    public function register(array $data):Shiper
    {
        $shipper = Shiper::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'status' => 'available',
        ]);
        return $shipper;
    }

    /**
     * Update an existing shipper.
     *
     * @param array $data
     * @param int $id
     *
     * @return mixed
     */
    public function update(array $data, int $id): mixed
    {
        $shipper = $this->find($id);
        if (!$shipper) {
            return false;
        }

        $shipper->update($data);

        return $shipper;
    }

    /**
     * Get all shippers with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAllWithPagination(): LengthAwarePaginator
    {
        return $this->getModel()::paginate($this->getModel()::ITEM_PER_PAGE);
    }

        /**
     * Verify the email of a shipper by ID.
     *
     * @param int $data
     *
     * @return Shiper
     */
    public function verifyEmail(int $data):Shiper
    {
        $shipper = $this->model::findOrFail($data);
        return $shipper;
    }

    /**
     * Retrieve a shipper by email.
     *
     * @param array $data An associative array containing the 'email' key.
     *
     * @return \Illuminate\Database\Eloquent\Model|null The shipper model instance if found, null otherwise.
     */
    public function getShipper(array $data): mixed
    {
        $shipper = $this->getModel()::where('email', $data['email'])->first();
        return $shipper;
    }
}
