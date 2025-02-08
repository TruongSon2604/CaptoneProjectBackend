<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    const ITEM_PER_PAGE=5;

    protected $fillable=[
        'name',
        'description',
        'price',
        'image',
        'categories_id',
        'stock_quantity',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the category that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the order details associated with the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderDetails(): HasMany{
        return $this->hasMany(OrderDetail::class);
    }


    /**
     * Get the comments for the product.
     *
     * This function defines a one-to-many relationship between the Product model
     * and the Comment model. It indicates that a product can have multiple comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment(): HasMany{
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the comments associated with the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cart(): HasMany{
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the discount associated with this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function discount(): HasOne{
        return $this->hasOne(Discount::class);
    }

    /**
     * This function calculates the factorial of a given number.
     *
     * @return float The factorial of the given number.
     */
    public function getDiscountedPriceAttribute(): mixed
    {
        if ($this->discount) {
            return $this->price * (1 - ($this->discount->percent_discount / 100));
        }
        return $this->price;
    }
}
