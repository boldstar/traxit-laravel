<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = 
    [
        'category',
        'first_name', 
        'middle_initial', 
        'last_name', 
        'occupation', 
        'email',
        'cell_phone',
        'work_phone',
        'spouse_first_name',
        'spouse_middle_initial',
        'spouse_last_name',
        'spouse_occupation',
        'spouse_dob',
        'spouse_email',
        'spouse_cell_phone',
        'spouse_work_phone',
        'street_address',
        'city',
        'state',
        'postal_code',
        'created_at',
        'updated_at',
    ];

    public function account() 
    {
        return $this->hasOne('App\Account')
    }
}
