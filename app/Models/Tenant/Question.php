<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Question extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = 
    [
        'engagement_id',
        'question',
        'answer',
        'answered',
        'created_at',
    ];

    protected $hidden = ['updated_at'];

    public function engagement() {
        return $this->belongsTo('App\Models\Tenant\Engagement');
    }
}
