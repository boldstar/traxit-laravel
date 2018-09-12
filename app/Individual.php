<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    public function engagement() {
        return $this->belongsTo('App\Engagement')
    }
}
