<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Task extends Model
{
    use UsesTenantConnection;
    
    protected $fillable =
    [
        'user_id',
        'title',
    ];

    protected $hidden =
    [
        'updated_at'
    ];

    public function user() {
        return $this->belongsTo('App\Models\Tenant\User');
    }

    public function engagements()
    {
        return $this->belongsToMany('App\Models\Tenant\Engagement', 'engagement_task', 'task_id', 'engagement_id');
    }

}
