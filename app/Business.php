<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
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
        'email_address'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function client() {
        return $this->belongsTo('App\Client');
    }
}
