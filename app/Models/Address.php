<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;
    // protected $fillable=[
    //     'user_id',
    //     'district',
    //     'provice',
    //     'ward',
    //     'address_detail',
    //     'phone'
    // ];
    protected $guarded = [];
}
