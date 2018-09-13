<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Engagement extends Model
{
    public function client() 
    {
        return $this->belongsTo('App\Client');
    }

    public function engagement_tasks()
    {
        return $this->belongsToMany('App\Task');
    }

}
