<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Customization extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = 
    [
        'belongs_to',
        'name'
    ];

}
