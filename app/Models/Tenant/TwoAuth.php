<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TwoAuth extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = [
        'email',
        'phone_number',
        'token',
        'expires_on'
    ];
}
