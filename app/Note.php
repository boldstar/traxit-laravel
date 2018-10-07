<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable =
    [
        'client_id',
        'note',
    ];

    protected $hidden = 
    [
        'created_at',
        'updated_at'
    ];

    public function client() 
    {
        return $this->belongsTo('App\Client');
    }
}
