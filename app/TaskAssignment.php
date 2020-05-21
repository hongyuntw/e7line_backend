<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'task_assignments';

    protected $guarded = ['id',];


    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task_reply_msgs()
    {
        return $this->hasMany(TaskReplyMsg::class);
    }

}
