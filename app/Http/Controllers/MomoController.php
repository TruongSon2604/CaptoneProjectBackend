<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MomoController extends Controller
{
    public function createPayment(Request $request)
    {
        $client = new Client();

        $storeName = "CoffeeMart";
        $accessKey = 'F8BBA842ECF85';
        $secretKey = 'K951B6PE1waDMi640xX08PD3vg6EkVlz';
        $orderInfo = 'pay with MoMo';
        $partnerCode = 'MOMO';
        $redirectUrl = 'https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b';
        $ipnUrl = 'https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b';
        $amount = '50000';
        $orderId = time() . "";
        $requestId = time() . "";
        $extraData = '';
        $requestType = 'payWithMethod';
        $partnerName = 'MoMo Payment';
        $storeId = 'Test Store';
        $orderGroupId = '';
        $autoCapture = true;
        $lang = 'vi';

        // Thông tin giao dịch
        $orderId = uniqid();  // Tạo mã đơn hàng duy nhất
        $amount = $request->input('amount');  // Số tiền thanh toán
        $orderInfo = "Thanh toán đơn hàng #" . $orderId;
        $requestId = time();  // ID yêu cầu
        $extraData = "";  // Dữ liệu bổ sung (nếu có)

        // Dữ liệu thanh toán
        $data = [
            "partnerCode" => $partnerCode,
            "accessKey" => $accessKey,
            "requestId" => $requestId,
            "amount" => $amount,
            "orderId" => $orderId,
            "orderInfo" => $orderInfo,
            "redirectUrl" => $redirectUrl,
            "ipnUrl" => $redirectUrl,
            "extraData" => $extraData,
            "requestType" => $requestType,  // Thêm trường này vào
        ];

        // Tạo chữ ký (signature)
        $signature = $this->generateSignature($data, $secretKey);
        $data['signature'] = $signature;
        Log::info("Signature: " . $signature);
        Log::info("Data: " . json_encode($data));  // Log lại dữ liệu gửi đi

        // Gửi yêu cầu đến Momo API
        try {
            $response = $client->post('https://test-payment.momo.vn/v2/gateway/api/create', [
                'json' => $data,
            ]);

            // Nhận phản hồi từ MoMo
            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info("Response from MoMo: " . json_encode($responseBody));

            // Kiểm tra kết quả trả về từ MoMo
            if (isset($responseBody['resultCode']) && $responseBody['resultCode'] == 0) {
                // Điều hướng người dùng đến trang thanh toán của MoMo
                return redirect()->to($responseBody['payUrl']);
            } else {
                Log::error("Error response: " . json_encode($responseBody));  // Log lỗi chi tiết
                return redirect()->back()->withErrors(['message' => 'Lỗi khi tạo giao dịch.']);
            }
        } catch (\Exception $e) {
            Log::error("Exception: " . $e->getMessage());
            return redirect()->back()->withErrors(['message' => 'Không thể kết nối với Momo: ' . $e->getMessage()]);
        }
    }

    // Phương thức tạo chữ ký
    private function generateSignature($data, $secretKey)
    {
        // Sắp xếp dữ liệu theo key (alphabetical order)
        ksort($data);

        // Tạo chuỗi raw data
        $rawData = '';
        foreach ($data as $key => $value) {
            if ($key != 'signature' && $value != '') {  // Loại bỏ chữ ký khỏi chuỗi raw và các giá trị rỗng
                $rawData .= $key . '=' . $value . '&';
            }
        }

        // Thêm secretKey vào cuối chuỗi
        $rawData .= 'key=' . $secretKey;

        // Log rawData trước khi tạo chữ ký
        Log::info("Raw Data before hash: " . $rawData);

        // Băm MD5 và chuyển thành chữ hoa
        return strtoupper(md5($rawData));
    }

    public function paymentNotify(Request $request)
    {
        $response = $request->all();

        $signature = $response['signature'];
        unset($response['signature']);
        if ($signature == $this->generateSignature($response, env('MOMO_SECRET_KEY'))) {
            if ($response['resultCode'] == '0') {
                Log::info("Giao dich thanh cong");
                return redirect()->route('payment.success');
            } else {
                return redirect()->route('payment.failure');
            }
        }
        return redirect()->route('payment.failure');
    }
}
