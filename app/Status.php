<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'status';

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
