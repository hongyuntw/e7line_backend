<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    //
    protected $table = 'members';
    protected $fillable = [
        'name',
        'email',
        'password'
    ];
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
