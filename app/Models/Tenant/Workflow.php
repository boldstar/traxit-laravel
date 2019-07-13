<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Workflow extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'id',
        'workflow',
        'engagement_type',
        'active'
    ];

    public function statuses()
    {
        return $this->hasMany('App\Models\Tenant\Status');
    }

    public function engagements()
    {
        return $this->hasMany('App\Models\Tenant\Engagement');
    }
}
