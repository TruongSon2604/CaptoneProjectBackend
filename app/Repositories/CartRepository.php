<?php

namespace App\Repositories;

use App\Contracts\CartInterface;
use App\Models\Cart;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $cartITems = DB::table('carts')
            ->leftJoin('users', 'carts.user_id', '=', 'users.id')
            ->leftJoin('products', 'carts.products_id', '=', 'products.id')
            ->leftJoin('discounts', 'discounts.product_id', '=', 'products.id')
            ->selectRaw('
                ROUND(carts.quantity * products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as sub_total
            ')->where('users.id', Auth::id())->get();
        $total = $cartITems->sum('sub_total');
        return $total;
    }

    public function getCartItem()
    {
        // $cartITems= $this->model::where(column: 'user_id',Auth::user()->id)
        // ->with('product')->get();
        $cartITems = DB::table('carts')
            ->leftJoin('users', 'carts.user_id', '=', 'users.id')
            ->leftJoin('products', 'carts.products_id', '=', 'products.id')
            ->leftJoin('discounts', 'discounts.product_id', '=', 'products.id')
            ->selectRaw('
                products.id,
                products.name,
                products.stock_quantity,
                carts.quantity,
                products.image,
                products.description,
                products.price as original_price,
                COALESCE(discounts.percent_discount, 0) as discount_percent,
                ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price,
                ROUND(carts.quantity * products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as sub_total
            ')->where('users.id',Auth::id())->get();
        return [$cartITems,"count"=>$cartITems->count()];
    }

    public function addMultipleToCart(array $data)
    {
        $userId = Auth::id();
        $products = $data['products'];
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

    public function updateQuantityCart(array $data)
    {
        if ($data['quantity'] > 0) {
            $item = $this->model::where('user_id', Auth::id())->where('products_id', $data['product_id']);
            $item->update(
                ['quantity' => $data['quantity']]
            );
            $cartITems = DB::table('carts')
                ->leftJoin('users', 'carts.user_id', '=', 'users.id')
                ->leftJoin('products', 'carts.products_id', '=', 'products.id')
                ->leftJoin('discounts', 'discounts.product_id', '=', 'products.id')
                ->selectRaw('
                ROUND(carts.quantity * products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as sub_total
            ')->where('products.id', $data['product_id'])->get();
            return $cartITems;
        }
    }

    public function deleteMoreItemFromCart(array $data)
    {
        $arr2 = $data["data"];
        $deletedItems = [];
        Log::info("message");
        foreach ($arr2 as $item){
            $cartItem = $this->model::where('user_id', Auth::user()->id)
                ->where('products_id',  $item['product_id'])
                ->first();

            if ($cartItem) {
                $cartItem->delete();
                $deletedItems[] = $cartItem;
            }
        }
        return $deletedItems;
    }

    public function getProductByListId(array $data)
    {
        $cartITems = DB::table('carts')
            ->leftJoin('users', 'carts.user_id', '=', 'users.id')
            ->leftJoin('products', 'carts.products_id', '=', 'products.id')
            ->leftJoin('discounts', 'discounts.product_id', '=', 'products.id')
            ->selectRaw('
                products.id,
                products.name,
                products.stock_quantity,
                carts.quantity,
                products.image,
                products.description,
                products.price as original_price,
                COALESCE(discounts.percent_discount, 0) as discount_percent,
                ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price,
                ROUND(carts.quantity * products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as sub_total
            ')->where('users.id',Auth::id())->whereIn('products_id',$data['products_id'])->get();
            $total = $cartITems->sum('sub_total');
            return [$cartITems,"total"=>$total];

        // $data = $this->model::where('user_id',Auth::id())->whereIn('products_id',$data['products_id'])->with('product')->get();
        // return $data;
    }
}
