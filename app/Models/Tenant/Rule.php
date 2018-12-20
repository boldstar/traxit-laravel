<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Rule extends Model
{
    use UsesTenantConnection;
    
    protected $hidden = [
        'id',
        'role_id',
        'created_at',
        'updated_at'
    ];

    public function role() {
        return $this->belongsTo('App\Models\Tenant\Role');
    }
}
