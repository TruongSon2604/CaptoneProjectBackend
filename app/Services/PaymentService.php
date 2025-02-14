<?php

namespace App\Services;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class PaymentService
{
    public function __construct(protected PaymentRepository $paymentRepository)
    {

    }

    // public function checkAndSavePayment(array $dataOrder)
    // {
    //     $transaction_id = $dataOrder['transaction_id'];
    //     $response = Http::async()->get("http://127.0.0.1:8000/api/payment2/status/" . $transaction_id);

    //     if ($response->successful()) {
    //         $data = $response->json()['original']['data'];

    //         if ($data['return_code'] === 1) {
    //             Payment::updateOrCreate([
    //                 'orders_id' => $dataOrder['orders_id'],
    //                 'payment_method_id' => $dataOrder['payment_method_id'],
    //                 'status_id' => 1,
    //                 'transaction_id' => $dataOrder['transaction_id']
    //             ]);
    //             return "Đơn hàng {$transaction_id} đã thanh toán thành công.";
    //         } elseif ($data['return_code'] === 3) {
    //             return "Đơn hàng {$transaction_id} chưa được thanh toán.";
    //         } else {
    //             return "Giao dịch thất bại hoặc không hợp lệ.";
    //         }
    //     }

    //     return "Không thể kiểm tra trạng thái đơn hàng {$transaction_id}.";
    // }

    public function UpdatePaymentOrder(array $data)
    {
        return $this->paymentRepository->UpdatePaymentOrder($data);
    }
}
