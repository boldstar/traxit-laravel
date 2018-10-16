<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Engagement extends Model
{
    protected $fillable =
    [
        'client_id',
        'return_type',
        'year',
        'assigned_to',
        'status',
    ];
    public function client() 
    {
        return $this->belongsTo('App\Client');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task', 'engagement_task', 'engagement_id', 'task_id');
    }

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function user()
    {
        return $this->hasManyThrough('App\User', 'App\Task');
    }

}
