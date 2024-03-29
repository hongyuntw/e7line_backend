<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRelation extends Model
{
    //

    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'product_relations';

    protected $guarded = ['id'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function product_detail()
    {
        return $this->belongsTo(ProductDetail::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function isbn_relations()
    {
        return $this->hasMany(IsbnRelation::class);
    }


}
