<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZaloPayController extends Controller
{
    //
    public function payment(Request $request)
    {
        $config = [
            "app_id" => 2554,
            "key1" => "sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn",
            "key2" => "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf",
            "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
        ];

        $embeddata = '{}'; // Merchant's data
        $items = '[]'; // Merchant's data
        // $transID = rand(0,1000000); //Random trans id

        // Retrieve `userid` and `total` from the request
        $userId = $request->input('userid');
        $transID = $request->input('orderid');
        $totalAmount = $request->input('total');
        $order = [
            "app_id" => $config["app_id"],
            "app_time" => round(microtime(true) * 1000), // miliseconds
            "app_trans_id" => date("ymd_His") . "_" . $transID, // translation missing: vi.docs.shared.sample_code.comments.app_trans_id
            "app_user" => $userId,
            "item" => $items,
            "embed_data" => $embeddata,
            "amount" => $totalAmount,
            "description" => "Lazada - Payment for the order #$transID of Userid #$userId",
            "bank_code" => "zalopayapp",
            "callback_url" => "http://127.0.0.1:8000/api/payment/callback"
        ];

        $data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"]
            . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $config["key1"]);

        $context = stream_context_create([
            "http" => [
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($order)
            ]
        ]);

        $resp = file_get_contents($config["endpoint"], false, $context);
        $result = json_decode($resp, true);

        return response()->json([
            'status' => true,
            'message' => $resp,
            'data' => $result
        ]);
    }

    public function get_status($iddh)
    {

        $config = [
            "app_id" => 2554,
            "key1" => "sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn",
            "key2" => "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf",
            "endpoint" => "https://sb-openapi.zalopay.vn/v2/query",
        ];

        $app_trans_id = $iddh;
        $data = $config["app_id"] . "|" . $app_trans_id . "|" . $config["key1"];
        $params = [
            "app_id" => $config["app_id"],
            "app_trans_id" => $app_trans_id,
            "mac" => hash_hmac("sha256", $data, $config["key1"])
        ];

        $context = stream_context_create([
            "http" => [
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($params)
            ]
        ]);

        $resp = file_get_contents($config["endpoint"], false, $context);
        $result = json_decode($resp, true);

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $result
        ]);
    }
}
