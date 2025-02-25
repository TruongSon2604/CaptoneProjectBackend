<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCoupon extends Model
{
    use HasFactory;
    const ITEM_PER_PAGE=5;
    protected $guarded = [];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class,'coupon_id','id');
    }
}
