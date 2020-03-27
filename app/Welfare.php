<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Welfare extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'welfares';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function welfarestatus()
    {
        return $this->hasMany(WelfareStatus::class);
    }


}
