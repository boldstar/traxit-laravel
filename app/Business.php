<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    public function engagement() {
        return $this->belongsTo('App\Engagement')
    }
}
