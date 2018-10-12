<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = 
    [
        'engagement_id',
        'question',
        'answer',
        'answered',
        'created_at',
    ];

    protected $hidden = ['updated_at'];

    public function engagement() {
        return $this->belongsTo('App\Engagement');
    }
}
