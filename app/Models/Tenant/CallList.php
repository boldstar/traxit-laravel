<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class CallList extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'engagement_id',
        'engagement_name',
        'current_status',
        'user_name',
        'comments',
        'total_calls',
        'first_called_date',
        'last_called_date'
    ];

    public function engagement() 
    {
        return $this->belongsTo('App\Models\Tenant\Engagement');
    }
}
