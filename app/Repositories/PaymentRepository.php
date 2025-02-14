<?php

namespace App\Repositories;

use App\Contracts\PaymentInterface;
use App\Models\Payment;
use App\Repositories\BaseRepository;

class PaymentRepository extends BaseRepository implements PaymentInterface
{
    public function getModel()
    {
        return Payment::class;
    }

    public function UpdatePaymentOrder(array $data)
    {
        $payment = Payment::create([
            'orders_id' => $data['orders_id'],
            'payment_method_id' => $data['payment_method_id'],
            'status' => 1,
            'transaction_id' => $data['transaction_id']
        ]);
        return $payment;
    }
}
