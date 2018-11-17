<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    protected $table = 'sales';
    protected $fillable = [
        'user_id',
        'order_name',
        'order_phone',
        'order_note',
        'order_address',
        'order_date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function salesitem()
    {
        return $this->hasMany(SalesItem::class);
    }
}
