<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'saleprice',
        'listprice',
        'unit',
        'description',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function salesitem()
    {
        return $this->hasMany(SalesItem::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
