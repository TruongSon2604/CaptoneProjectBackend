<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZaloPayRequest;
use App\Services\ZaloPayService;
use Illuminate\Http\Request;

class ZaloPayOrderController extends Controller
{
    public function __construct(protected ZaloPayService $zaloPayService)
    {

    }

    public function payment(ZaloPayRequest $zaloPayRequest)
    {
        $status= $this->zaloPayService->payment(($zaloPayRequest->validated()));
        if($status)
        {
            return response()->json($status);
        }
    }

    public function get_status($iddh)
    {
        $status= $this->zaloPayService->get_status($iddh);
        if($status)
        {
            return response()->json($status);
        }
    }

    public function paymentCallback(Request $request)
    {
        return $this->zaloPayService->paymentCallback($request);
    }
}
