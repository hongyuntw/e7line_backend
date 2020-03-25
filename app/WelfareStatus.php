<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WelfareStatus extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'welfare_status';

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function welfare()
    {
        return $this->belongsTo(Welfare::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
