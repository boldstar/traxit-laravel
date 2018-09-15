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

    public function engagement_tasks()
    {
        return $this->belongsToMany('App\Task');
    }

}
