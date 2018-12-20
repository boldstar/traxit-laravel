<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Account extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = [
        'business_name',
        'address',
        'city',
        'state',
        'postal_code',
        'email',
        'phone_number',
        'fax_number',
        'logo',
        'subscription',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
