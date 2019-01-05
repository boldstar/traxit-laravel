<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Client extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = 
    [
        'active',
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
        return $this->hasMany('App\Models\Tenant\Engagement');
    }

    public function dependents()
    {
        return $this->hasMany('App\Models\Tenant\Dependent');
    }

    public function notes()
    {
        return $this->hasMany('App\Models\Tenant\Note');
    }

    public function businesses()
    {
        return $this->hasMany('App\Models\Tenant\Business');
    }

    public function fullName() {

        return "{$this->last_name}, {$this->first_name}";
    
    }

    public function fullNameWithSpouse()
    {
        if (!is_null($this->spouse_first_name) && $this->has_spouse == true)
        return $this->fullName() . " & " . $this->spouse_first_name;

        return $this->fullName();  // if no spouse
    }
}
