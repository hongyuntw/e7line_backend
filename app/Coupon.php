<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'type',
        'is_used',
    ];
}
