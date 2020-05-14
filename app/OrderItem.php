<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'order_items';
    protected $guarded = ['id'];

    public function product_relation()
    {
        return $this->belongsTo(ProductRelation::class);

    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
