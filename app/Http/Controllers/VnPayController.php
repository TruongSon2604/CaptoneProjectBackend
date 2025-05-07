<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class VnpayController extends Controller
{
    public $vnref;
    public function __construct(protected OrderService $orderService)
    {

    }
    public function createPayment(Request $request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $app_trans_id = date("ymd_Hi") . "_" . mt_rand(100, 999);
            // Get the amount from the request
            $embeddata = json_encode([
                "address_id" => $request['address_id'],
                "coupon_id" => $request['coupon_id'] ?? null,
                "user_id" => Auth::id(),
                "app_trans_id" => $app_trans_id
            ]);
            $items = json_encode($request['cartItems']);
            $itemsBase64 = base64_encode($items);
            $vnp_TxnRef = time();
            Cache::put("vnpay_data_{$vnp_TxnRef}", [
                'embeddata' => $embeddata,
                'cartItems' => $request['cartItems'],
                'user_id' => Auth::id(),

            ], now()->addMinutes(30));

            // $tongtien = $request->input('sotien');
            $dataInput = [
                'address_id' => $request->input('address_id'),
                'coupon_id' => $request->input('coupon_id', null),
                'cartItems' => $request->input('cartItems', []),
            ];

            // Gọi hàm xử lý
            $totalAmount = $this->orderService->getFinalAmount($dataInput);

            $vnp_TmnCode = "VWPBXIW4";
            $vnp_HashSecret = "TI9GAED46JYTYRNOV936B4FA6K60VEB4"; // Secret key
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; // VNPAY URL
            $vnp_Returnurl = "https://3730-14-191-113-227.ngrok-free.app/api/vnpay/return"; // Return URL after payment
            $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";

            // Get current time and expiration time
            $startTime = date("YmdHis");
            $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

            // Transaction details
            $vnp_OrderInfo = 'Thanh toán đơn hàng đặt tại web'; // Your order info
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $totalAmount * 100; // Convert the amount to the smallest unit
            $vnp_Locale = 'vn'; // Vietnamese locale
            $vnp_IpAddr = $request->ip();

            // Prepare input data for VNPAY request
            $inputData = [
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => $startTime,
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate" => $expire,
                "vnp_Bill_Address" => $request['address_id'],
                "vnp_Bill_City" => $request['coupon_id'],
                "vnp_Bill_Country" => Auth::id(),
                "vnp_Inv_Phone" => $itemsBase64
            ];

            // Sort the input data array by key
            ksort($inputData);

            // Generate hash data string
            $hashdata = http_build_query($inputData); // Convert array to query string
            $vnp_Url .= '?' . $hashdata;

            // Generate the secure hash with HMAC SHA512
            $vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= '&vnp_SecureHash=' . $vnp_SecureHash;

            // Log URL and hash for debugging
            Log::info('VNPAY URL: ' . $vnp_Url);

            // Return the data with URL to redirect (show QR code or redirect user to payment gateway)
            return response()->json([
                'code' => '00',
                'message' => 'success',
                'transaction_id' => $app_trans_id,
                'data' => $vnp_Url
            ]);
        } catch (\Exception $e) {
            // Log the error and return a response
            Log::error('Error generating VNPAY URL: ' . $e->getMessage());

            return response()->json([
                'code' => '99',
                'message' => 'error',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function vnpayReturn(Request $request)
    {
        try {
            // VNPAY Secret Key
            $vnp_HashSecret = "TI9GAED46JYTYRNOV936B4FA6K60VEB4";
            $vnp_Params = $request->all();

            // Tạo chuỗi hash từ các tham số trả về (bỏ qua tham số vnp_SecureHash)
            $secureHash = $vnp_Params['vnp_SecureHash'];
            unset($vnp_Params['vnp_SecureHash']);
            ksort($vnp_Params);
            $hashData = http_build_query($vnp_Params);

            // Tính toán HMAC SHA512 của dữ liệu đã sắp xếp và so sánh với vnp_SecureHash trả về từ VNPAY
            $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

            // Kiểm tra mã bảo mật có khớp không
            if ($secureHash === $vnp_SecureHash) {
                // Mã bảo mật hợp lệ
                $vnp_ResponseCode = $vnp_Params['vnp_ResponseCode'];

                if ($vnp_ResponseCode == '00') {
                    // Thanh toán thành công
                    $vnp_TxnRef = $vnp_Params['vnp_TxnRef']; // Mã giao dịch
                    $vnp_Amount = $vnp_Params['vnp_Amount']; // Số tiền giao dịch

                    // Retrieve data from cache
                    $data = Cache::get("vnpay_data_{$vnp_TxnRef}");

                    $embedData = json_decode($data['embeddata'], true);

                    $parsedData = [
                        "address_id" => $embedData['address_id'] ?? null,
                        "coupon_id" => $embedData['coupon_id'] ?? null,
                        "user_id" => $embedData['user_id'] ?? null,
                        "cartItems" => $data['cartItems'] ?? [],
                        "transaction_id" => $embedData['app_trans_id'] ?? null,
                    ];
                    Log::info("------------------");
                    Log::info("Parsed Data: ", $parsedData);

                    $orderZalo = $this->orderService->createOrderZalo($parsedData);
                    Log::info("ZaloPay Payment Success: Transaction {$orderZalo}");
                    return response()->json([
                        'code' => '00',
                        'message' => 'Thanh toán thành công!',
                        'txn_ref' => $vnp_TxnRef,
                        'amount' => $vnp_Amount / 100,  // Chuyển đổi lại về đơn vị tiền tệ ban đầu
                    ]);
                } else {
                    return response()->json([
                        'code' => '01',
                        'message' => 'Thanh toán không thành công, vui lòng thử lại!',
                        'response_code' => $vnp_ResponseCode,
                    ]);
                }
            } else {
                // Mã bảo mật không hợp lệ
                return response()->json([
                    'code' => '99',
                    'message' => 'Lỗi bảo mật! Dữ liệu không hợp lệ.',
                ], 500);
            }
        } catch (\Exception $e) {
            // Log lỗi và trả về phản hồi lỗi
            Log::error('Error processing VNPAY return: ' . $e->getMessage());

            return response()->json([
                'code' => '99',
                'message' => 'Lỗi khi xử lý giao dịch.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function checkPaymentStatus($transaction_id)
    {
        Log::info("transaction_id: " . $transaction_id);
        $hasOrder = Order::where('transaction_id', $transaction_id)->exists();
        return response()->json([
            'status' => $hasOrder ? 'success' : 'pending'
        ]);
    }
}
