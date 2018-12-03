<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    //
    protected $table = 'ads';

    protected $fillable = [
        'imagename',
        'name',
        'text_1',
        'text_2',
        'text_3',

    ];

}
