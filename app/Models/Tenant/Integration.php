<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Integration extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'name',
        'expires',
        'issues',
        'expires_in',
        'mfa_token',
        'refresh_token',
        'token_type',
        'user_id',
        'active'
    ];
}
