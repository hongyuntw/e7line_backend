<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessConcatPerson extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'business_concat_persons';

    protected $guarded = ['id', 'create_date'];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
