<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Shiper extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    protected $table = 'shippers';
    const ITEM_PER_PAGE=5;

    protected $fillable = [
        'name', 'phone', 'email', 'password', 'latitude', 'longitude', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
