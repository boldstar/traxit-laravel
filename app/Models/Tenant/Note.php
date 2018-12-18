<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Note extends Model
{
    use UsesTenantConnection;
    
    protected $fillable =
    [
        'client_id',
        'note',
    ];

    protected $hidden = 
    [
        'created_at',
        'updated_at'
    ];

    public function client() 
    {
        return $this->belongsTo('App\Models\Tenant\Client');
    }
}
