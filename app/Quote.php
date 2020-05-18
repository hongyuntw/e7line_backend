<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'quotes';
    protected $guarded = ['id'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
