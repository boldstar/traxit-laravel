<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    public function statuses()
    {
        return $this->hasMany('App\Status');
    }
}
