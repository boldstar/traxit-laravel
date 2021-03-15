<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Setting extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = 
    [
        
    ];
}
