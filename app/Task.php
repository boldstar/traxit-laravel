<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function user() {
        return $this->belongsTo('App\User')
    }

    public function task_engagements()
    {
        return $this->belongsToMany('App\Engagement', 'engagement_task', 'task_id', 'engagement_id')
    }
}
