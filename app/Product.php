<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'products';

    protected $guarded = ['id'];


    public function product_relations()
    {
        return $this->hasMany(ProductRelation::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }


}
