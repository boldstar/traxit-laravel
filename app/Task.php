<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable =
    [
        'user_id',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function engagements()
    {
        return $this->belongsToMany('App\Engagement', 'engagement_task', 'task_id', 'engagement_id');
    }

}
