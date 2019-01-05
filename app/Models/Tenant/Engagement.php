<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Engagement extends Model
{
    use UsesTenantConnection;
    
    protected $fillable =
    [
        'category',
        'title',
        'type',
        'description',
        'client_id',
        'name',
        'workflow_id',
        'return_type',
        'year',
        'assigned_to',
        'status',
    ];
    public function client() 
    {
        return $this->belongsTo('App\Models\Tenant\Client');
    }

    public function workflow() 
    {
        return $this->belongsTo('App\Models\Tenant\Workflow');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Models\Tenant\Task', 'engagement_task', 'engagement_id', 'task_id');
    }

    public function questions()
    {
        return $this->hasMany('App\Models\Tenant\Question');
    }

}
