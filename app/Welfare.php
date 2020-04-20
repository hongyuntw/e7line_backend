<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Welfare extends Model
{
    //

    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'welfares';
    protected $guarded = ['id'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
