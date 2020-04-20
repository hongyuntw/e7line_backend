<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'orders';

    protected $guarded = ['id', 'create_date'];


    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function business_concat_person()
    {
        return $this->belongsTo(BusinessConcatPerson::class);
    }

    public function welfare()
    {
        return $this->belongsTo(Welfare::class);
    }




}
