<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'customers';

    protected $guarded = ['id', 'create_date', 'update_date'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function welfarestatus()
    {
        return $this->hasMany(WelfareStatus::class);
    }

    public function business_concat_persons()
    {
        return $this->hasMany(BusinessConcatPerson::class);
    }

    public function concat_records()
    {
        return $this->hasMany(ConcatRecord::class);
    }

}
