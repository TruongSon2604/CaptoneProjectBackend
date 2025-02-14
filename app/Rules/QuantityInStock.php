<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class QuantityInStock implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure  $fail
     * 
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $itemIndex = explode('.', $attribute)[1];
        $productId = request()->input("cartItems.$itemIndex.product_id");
        $quantity = $value;

        $product = Product::find($productId);

        if ($product && $product->stock_quantity < $quantity) {
            $fail('The quantity for product ' . $product->name . ' exceeds the available stock.');
        }
    }
}
