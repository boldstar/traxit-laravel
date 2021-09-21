<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class BusinessNote extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'business_id',
        'note'
    ];

    public function business() {
        return $this->belongsTo('App\Models\Tenant\Business');
    }
}
