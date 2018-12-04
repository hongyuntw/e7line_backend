<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    //
    protected $table = 'suggestions';

    protected $fillable = [
        'email',
        'text',
        'name',
    ];
}
