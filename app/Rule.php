<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $hidden = [
        'id',
        'role_id',
        'created_at',
        'updated_at'
    ];

    public function role() {
        return $this->belongsTo('App\Role');
    }
}
