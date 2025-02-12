<?php

namespace App\Services;

use App\Repositories\CartRepository;

class CartService {
    public function __construct(protected CartRepository $cartRepository)
    {

    }

    public function addToCart(array $data)
    {
        return $this->cartRepository->addToCart($data);
    }

    public function updateCart(array $data)
    {
        return $this->cartRepository->updateCart($data);
    }

    public function removeFromCart(array $data)
    {
        return $this->cartRepository->removeFromCart($data);
    }

    public function calculateTotal()
    {
        return $this->cartRepository->calculateTotal();
    }

    public function getCartItem()
    {
        return $this->cartRepository->getCartItem();
    }

    public function addMultipleToCart(array $data)
    {
        $this->cartRepository->addMultipleToCart($data);
    }
}
