<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Bookkeeping extends Model
{
    Use UsesTenantConnection;

    protected $fillable = [
        'client_id',
        'belongs_to',
        'business_name',
        'account_name',
        'account_type',
        'year',
        'account_start_date',
        'account_close_date',
        'account_closed',
        'jan',
        'feb',
        'mar',
        'apr',
        'may',
        'jun',
        'jul',
        'aug',
        'sep',
        'oct',
        'nov',
        'dec'
    ];

    public function client()
    {
        return $this->belongsTo('App\Models\Tenant\Client');
    }
}
