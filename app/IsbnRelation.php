<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IsbnRelation extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'isbn_relations';
    protected $guarded = ['id'];


    public function senao_product()
    {
        return $this->belongsTo(SenaoProduct::class);
    }

    public function product_relation()
    {
        return $this->belongsTo(ProductRelation::class);
    }

}
