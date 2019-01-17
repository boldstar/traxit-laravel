<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class EngagementActions extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'user_id', 'engagement_id', 'workflow_id', 'engagement_model', 'action', 'category', 'type', 'title', 'name', 'year', 'return_type', 'status'
    ];

    public function engagement() {
        return $this->belongsTo('App\Modelsl\Tenant\Engagement', 'engagement_id');
    }
}
