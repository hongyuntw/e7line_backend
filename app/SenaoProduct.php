<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SenaoProduct extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'senao_products';
    protected $guarded = ['id'];


    public function isbn_relations()
    {
        return $this->hasMany(IsbnRelation::class);
    }
}
