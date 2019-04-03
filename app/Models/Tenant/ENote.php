<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ENote extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = [
        'engagement_id',
        'note',
        'username'
    ];

    public function engagement()
    {
        return $this->belongsTo('App\Models\Tenant\Engagement');
    }
}
