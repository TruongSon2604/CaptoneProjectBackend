<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    const ITEM_PER_PAGE= 5;

    protected $fillable = [
        'name',
    ];
}
