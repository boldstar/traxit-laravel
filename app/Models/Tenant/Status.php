<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Status extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = [
        'workflow_id',
        'status',
        'state',
        'order',
        'notify_client',
        'message',
        'created_at',
        'updated_at'
    ];
    
    public function workflow()
    {
        return $this->belongsTo('App\Models\Tenant\Workflow');
    }

}
