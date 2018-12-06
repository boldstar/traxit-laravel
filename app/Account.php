<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'business_name',
        'address',
        'city',
        'state',
        'postal_code',
        'email',
        'phone_number',
        'logo',
        'subscription',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
