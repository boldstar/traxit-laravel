<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function users() {
        return $this->belongsToMany('App\User', 'user_role', 'role_id', 'user_id');
    }

    public function subjects() {
        return $this->hasMany('App\Subject');
    }
}
