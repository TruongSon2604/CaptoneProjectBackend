<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'latitude',
        'longitude',
        'order_id',
        'shipper_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shipper()
    {
        return $this->belongsTo(Shiper::class);
    }
}
