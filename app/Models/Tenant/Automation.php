<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Automation extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'category',
        'workflow_id',
        'workflow',
        'status_id',
        'status',
        'action_id',
        'action',
        'active'
    ];
}
