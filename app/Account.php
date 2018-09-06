<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    
    public function client() {
        return $this->belongsTo('App\Client')
    }

    public function individual() {
        return $this->hasOne('App\Individual')
    }

    public function business() {
        return $this->hasMany('App\Business')
    }

}
