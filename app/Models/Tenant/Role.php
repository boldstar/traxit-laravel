<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Role extends Model
{
    use UsesTenantConnection;
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function users() {
        return $this->belongsToMany('App\Models\Tenant\User', 'user_role', 'role_id', 'user_id');
    }

    public function rules() {
        return $this->hasMany('App\Models\Tenant\Rule');
    }
}
