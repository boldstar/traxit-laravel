<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Tour extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'setup_tour',
        'default_tour',
        'admin_tour'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
