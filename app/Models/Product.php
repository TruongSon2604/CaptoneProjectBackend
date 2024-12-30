<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'created_at',
        'updated_at'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function orderDetails(): HasMany{
        return $this->hasMany(OrderDetail::class);
    }
    public function comment(): HasMany{
        return $this->hasMany(Comment::class);
    }
    public function cart(): HasMany{
        return $this->hasMany(Comment::class);
    }
}
