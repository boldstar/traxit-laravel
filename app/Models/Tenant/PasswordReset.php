<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PasswordReset extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = [
        'email', 'token'
    ];
}
