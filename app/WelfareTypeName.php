<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WelfareTypeName extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'welfare_type_names';
    protected $guarded = ['id'];

    public function welfare_types()
    {
        return $this->hasMany(WelfareType::class);
    }
}
