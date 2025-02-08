<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Services\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function __construct(protected LocationService $locationService)
    {

    }

    public function getNearestAvailableShipper(LocationRequest $request)
    {
        return $this->locationService->getNearestAvailableShipper($request->validated());
    }

    public function updateLocationOrder(Request $request)
    {
        return $this->locationService->updateLocationOrder($request->all());
    }
}
