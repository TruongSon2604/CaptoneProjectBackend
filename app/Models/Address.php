<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;
    const ITEM_PER_PAGE=5;
    protected $fillable=[
        'user_id',
        'district',
        'commune',
        'city',
        'address_detail',
        'phone'
    ];
}
