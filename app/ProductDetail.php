<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'product_details';

    protected $guarded = ['id', 'create_date'];

    public function product_relations()
    {
        return $this->hasMany(ProductRelation::class);
    }

}
