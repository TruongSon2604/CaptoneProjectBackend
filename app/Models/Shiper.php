<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\ShipperResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shiper extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, CanResetPassword;
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ShipperResetPasswordNotification($token));
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
