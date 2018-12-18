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
        return $this->belongsTo('App\User');
    }

    public function engagements()
    {
        return $this->belongsToMany('App\Engagement', 'engagement_task', 'task_id', 'engagement_id');
    }

}
