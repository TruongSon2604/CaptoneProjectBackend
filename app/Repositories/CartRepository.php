<?php

namespace App\Repositories;

use App\Contracts\CartInterface;
use App\Models\Cart;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CartRepository extends BaseRepository implements CartInterface
{
    public function getModel(): string
    {
        return Cart::class;
    }

    public function addToCart(array $data)
    {
        $userId = Auth::id();
        $cartItem = $this->model::where('user_id', $userId)
            ->where('products_id', $data['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $data['quantity']
            ]);
        } else {
            $this->model::create([
                'user_id' => $userId,
                'products_id' => $data['product_id'],
                'quantity' => $data['quantity']
            ]);
        }

        return $cartItem;
    }

    public function updateCart(array $data)
    {
        $cartItem = $this->model::where('user_id', Auth::user()->id)
            ->where('products_id', $data['product_id'])->firstOrFail();

        $cartItem->update([
            'quantity' => $data['quantity']
        ]);

        return $cartItem;
    }

    public function removeFromCart(array $data)
    {
        $cartItem = $this->model::where('user_id', Auth::user()->id)
            ->where('products_id', $data['product_id'])->firstOrFail();

        if ($cartItem) {
            $cartItem->delete();
            return $cartItem;
        }

        return null;
    }

    public function calculateTotal()
    {
        $userId = Auth::id();
        $cartItems = $this->model::where('user_id', $userId)
            ->with('product')
            ->get();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        return $total;
    }

    public function getCartItem()
    {
        $cartITems= $this->model::where('user_id',Auth::user()->id)
        ->with('product')->get();
        return $cartITems;
    }

    public function addMultipleToCart(array $data)
    {
        $userId = Auth::id();
        $products= $data['products'];
        foreach ($products as $product) {
            $productId = $product['product_id'];
            $quantity = $product['quantity'];

            $cartItem = $this->model::where('user_id', $userId)
                            ->where('products_id', $productId)
                            ->first();

            if ($cartItem) {
                $cartItem->update([
                    'quantity' => $cartItem->quantity + $quantity
                ]);
            } else {
                $this->model::create([
                    'user_id' => $userId,
                    'products_id' => $productId,
                    'quantity' => $quantity
                ]);
            }
        }
    }
}
