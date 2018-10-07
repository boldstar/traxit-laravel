<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = 
    [
        'engagement_id',
        'question',
        'answered'
    ];

    protected $hidden = ['updated_at'];

    public function engagement() {
        return $this->belongsTo('App\Engagement');
    }
}
