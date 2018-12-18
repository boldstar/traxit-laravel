<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Dependent extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = 
    [
        'client_id',
        'first_name', 
        'middle_name', 
        'last_name',  
        'dob',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function client()
    {
        return $this->belongsTo('App\Client');
    }
}
