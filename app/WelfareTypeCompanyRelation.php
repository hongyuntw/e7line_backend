<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WelfareTypeCompanyRelation extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'welfare_type_company_relations';
    protected $guarded = ['id'];

    public function welfare_types()
    {
        return $this->hasMany(WelfareType::class);
    }

    public function welfare_companies()
    {
        return $this->hasMany(WelfareCompany::class);
    }

    public function welfare_type()
    {
        return $this->belongsTo(WelfareType::class);

    }
    public function welfare_company()
    {
        return $this->belongsTo(WelfareCompany::class);

    }
}
