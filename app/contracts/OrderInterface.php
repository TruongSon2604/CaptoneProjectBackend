<?php

namespace App\Contracts;

interface OrderInterface extends BaseInterface
{
    public function create(array $data);

    public function updateOrderStatus(array $data);
}
