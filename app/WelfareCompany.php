<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WelfareCompany extends Model
{
    //

    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'welfare_companies';
    protected $guarded = ['id'];

    public function welfare_details()
    {
        return $this->hasMany(WelfareDetail::class);
    }

    public function welfare_statuses()
    {
        return $this->hasMany(WelfareStatus::class);
    }


    public function welfare_type_company_relation()
    {
        return $this->belongsTo(WelfareTypeCompanyRelation::class);
    }
}
