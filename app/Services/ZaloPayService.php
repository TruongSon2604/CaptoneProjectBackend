<?php

namespace App\Services;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ZaloPayService
{
    public function __construct(protected OrderService $orderService)
    {

    }
    public function payment(array $dataInput)
    {
        $config = [
            "app_id" => 2554,
            "key1" => "sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn",
            "key2" => "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf",
            "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
        ];

        // $embeddata = '{}';
        $embeddata = json_encode([
            "address_id" => $dataInput['address_id'],
            "coupon_id" => $dataInput['coupon_id'],
            "user_id" => Auth::id(),
        ]);

        $items = json_encode($dataInput['cartItems']);

        // $userId = $dataInput['user_id'];
        $userId = Auth::id();
        // $transID = $dataInput['transaction_id'];
        $total_amount = $dataInput['total_amount'];
        $order = [
            "app_id" => $config["app_id"],
            "app_time" => round(microtime(true) * 1000), // miliseconds
            "app_trans_id" => date("ymd_Hi") . "_" . mt_rand(100, 999), // translation missing: vi.docs.shared.sample_code.comments.app_trans_id
            "app_user" => $userId,
            "item" => $items,
            "embed_data" => $embeddata,
            "amount" => $total_amount,
            "description" => "Payment for the order of Userid #$userId",
            "bank_code" => "zalopayapp",
            "callback_url" => "https://aed1-14-191-112-76.ngrok-free.app/api/payment2/callback"
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

        return [
            "status" => true,
            "result" => $result
        ];
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


    public function paymentCallback(Request $request)
    {
        Log::info("Request toàn bộ dữ liệu:", $request->all());
        $config = [
            "key2" => "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf", // key2 dùng để verify callback
        ];

        $dataStr = $request->input('data');
        $reqMac = $request->input('mac');

        Log::info("ZaloPay Callback Triggered", ["data" => $dataStr, "mac" => $reqMac]);

        $mac = hash_hmac("sha256", $dataStr, $config["key2"]);
        if ($mac !== $reqMac) {
            return response()->json(["return_code" => -1, "return_message" => "Invalid MAC"], 400);
        }

        $data = json_decode($dataStr, true);
        Log::info("Decoded Callback Data", $data);

        $app_trans_id = $data["app_trans_id"] ?? null;
        $zp_trans_id = $data["zp_trans_id"] ?? null;
        $amount = $data["amount"] ?? 0;
        $status = $data["return_code"] ?? 1; // kiểm tra nếu không có return_code thì gán null

        // if ($status === null) {
        //     Log::error("ZaloPay Callback Error: Missing return_code", $data);
        //     return response()->json(["return_code" => -1, "return_message" => "Invalid response from ZaloPay"], 400);
        // }
        $items = json_decode($data['item'] ?? '[]', true);
        $embedData = json_decode($data['embed_data'], true);
        // Log::info("Parsed Items", $items);

        $parsedData = [
            "address_id" => $embedData['address_id'] ?? null,
            "coupon_id" => $embedData['coupon_id'] ?? null,
            "user_id" => $embedData['user_id'] ?? 1,
            "cartItems" => $items,
            "transaction_id" => $app_trans_id
            // "order_number" => $app_trans_id,
        ];
        Log::info("parsedData", $parsedData);
        if ($status == 1) {
            $status1 = Status::create([
                'name' => 'Đã thanh toán',
            ]);
            $orderZalo = $this->orderService->createOrderZalo($parsedData);
            Log::info("ZaloPay Payment Success: Transaction {$orderZalo} {$status1}");

        } else {
            Log::warning("ZaloPay Payment Failed: Transaction ID: $zp_trans_id, Amount: $amount");
        }

        return response()->json(["return_code" => 1, "return_message" => "Success"]);
    }


}
