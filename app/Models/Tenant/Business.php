<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Business extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = [
        'client_id',
        'business_name',
        'business_type',
        'address',
        'city',
        'state',
        'postal_code',
        'phone_number',
        'fax_number',
        'email',
        'ein',
        'tax_return_type',
        'state_tax_id',
        'sos_file_number',
        'xt_number',
        'rt_number',
        'formation_date',
        'twc_account',
        'qb_password',
        'active'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function client() {
        return $this->belongsTo('App\Models\Tenant\Client');
    }

    public function engagements() {
        return $this->belongsTo('App\Models\Tenant\Engagement');
    }

    public function services() {
        return $this->hasOne('App\Models\Tenant\BusinessService');
    }

    public function notes() {
        return $this->hasMany('App\Models\Tenant\BusinessNote');
    }
}
