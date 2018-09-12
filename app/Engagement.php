<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Engagement extends Model
{
    
    public function individual() {
        return $this->hasMany('App\Individual')
    }

    public function business() {
        return $this->hasMany('App\Business')
    }

}
