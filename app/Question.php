<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = 
    [
        'question',
        'answered'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function engagement() {
        return $this->belongsTo('App\Engagement');
    }
}
