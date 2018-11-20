<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'workflow_id',
        'status',
        'order',
        'created_at',
        'updated_at'
    ];
    
    public function workflow()
    {
        return $this->belongsTo('App\Workflow');
    }

}
