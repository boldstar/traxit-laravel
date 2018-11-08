<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $fillable = [
        'workflow'
    ];

    public function statuses()
    {
        return $this->hasMany('App\Status');
    }

    public function engagements()
    {
        return $this->hasMany('App\Engagement');
    }
}
