<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = 
    [
        'category',
        'referral_type', 
        'first_name', 
        'middle_initial', 
        'last_name', 
        'occupation', 
        'dob', 
        'email', 
        'cell_phone', 
        'work_phone',
        'has_spouse',
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
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function engagements()
    {
        return $this->hasMany('App\Engagement');
    }

    public function dependents()
    {
        return $this->hasMany('App\Dependent');
    }

    public function notes()
    {
        return $this->hasMany('App\Note');
    }


}
