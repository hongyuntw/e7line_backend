<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    //
    protected $table = 'sales_items';
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'sale_price',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
