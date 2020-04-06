<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WelfareType extends Model
{
    //

    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'welfare_types';
    protected $guarded = ['id'];

    public function welfare_type_company_relation()
    {
        return $this->belongsTo(WelfareTypeCompanyRelation::class);
    }


    public function welfare_status()
    {
        return $this->belongsTo(WelfareStatus::class);
    }

    public function welfare_type_name()
    {
        return $this->belongsTo(WelfareTypeName::class);
    }


}
