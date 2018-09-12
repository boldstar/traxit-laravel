<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Engagement extends Model
{
    
    public function clients() {
        return $this->belongsTo('App\Client')
    }

}
