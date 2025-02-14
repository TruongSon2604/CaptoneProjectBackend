<?php

namespace App\Contracts;

interface PaymentInterface extends BaseInterface
{
    public function UpdatePaymentOrder(array $data);
    // public function updateStatusOrder(array $data);
}
