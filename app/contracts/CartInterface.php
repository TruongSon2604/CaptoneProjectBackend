<?php

namespace App\Contracts;

interface CartInterface extends BaseInterface
{
    public function addToCart(array $data);

    public function updateCart(array $data);

    public function removeFromCart(array $data);

    public function calculateTotal();

    public function getCartItem();

    public function addMultipleToCart(array $data);
}
