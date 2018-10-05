<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dependent extends Model
{
    protected $fillable = 
    [
        'client_id',
        'first_name', 
        'middle_name', 
        'last_name',  
        'dob',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function client()
    {
        return $this->belongsTo('App\Client');
    }
}
