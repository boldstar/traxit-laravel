<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ReturnType extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = [
        'return_type'
    ];

    public $timestamps = false;
}
