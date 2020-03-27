<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WelfareDetail extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'welfare_details';
    protected $guarded = ['id'];

    public function welfare_company()
    {
        return $this->belongsTo(WelfareCompany::class);
    }

    public function welfare_statuses()
    {
        return $this->hasMany(WelfareStatus::class);
    }
}
