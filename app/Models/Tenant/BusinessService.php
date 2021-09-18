<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class BusinessService extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'business_id',
        'payroll',
        'sales_tax',
        'tax_return',
        'bookkeeping',
        'tax_planning',
        'note',
    ];

    public function business()
    {
        return $this->belongsTo('App\Models\Tenant\Business');
    }
}
