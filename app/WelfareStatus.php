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

// 這邊表示目的
    public function welfare()
    {
        return $this->belongsTo(Welfare::class);
    }

    public function welfare_company()
    {
        return $this->belongsTo(WelfareCompany::class);
    }

    public function welfare_detail()
    {
        return $this->belongsTo(WelfareDetail::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

//    這邊表示對應之福利為何（提貨卷之類）

    public function welfare_type()
    {
        return $this->belongsTo(WelfareType::class);
    }
}
