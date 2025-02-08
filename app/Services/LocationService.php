<?php

namespace App\Services;

use App\Models\Location;
use App\Models\Order;
use App\Repositories\LocationRepository;
use App\Repositories\ShipperRepository;

class LocationService
{
    public array $data = [];
   /**
     * locationRepository constructor.
     *
     * @param LocationRepository $locationRepository
     */
    public function __construct(protected LocationRepository $locationRepository, protected ShipperService $shipperRepository)
    {

    }

    public function getNearestAvailableShipper(array $data)
    {
        $this->data = $data;
        return $this->shipperRepository->getNearestAvailableShipper($data);
    }

    public function updateLocationOrder(array $data)
    {
        $order = Order::find($data['order_id']);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        if (!$order->shipper) {
            return response()->json(['message' => 'No shipper assigned to this order'], 404);
        }
        $shipper = $order->shipper;

        $location = Location::updateOrCreate(
            [
                'order_id' => $data['order_id'],
                'shipper_id' => $data['shipper_id'],
            ],
            [
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]
        );

        return response()->json([
            'message' => 'Shipper location updated successfully.',
            'location' => $location
        ]);
    }

}
