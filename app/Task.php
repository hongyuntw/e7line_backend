<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'tasks';

    protected $guarded = ['id',];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

