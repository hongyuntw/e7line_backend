<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SenaoOrder extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'senao_orders';
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
