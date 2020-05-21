<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskReplyMsg extends Model
{
    //
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'task_reply_msgs';

    protected $guarded = ['id',];


    public function task_assignment()
    {
        return $this->belongsTo(TaskAssignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
