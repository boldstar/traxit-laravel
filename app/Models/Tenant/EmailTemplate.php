<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class EmailTemplate extends Model
{
    use UsesTenantConnection;
    
    protected $fillable =
    [
        'title',
        'subject',
        'html_template',
        'text_template'
    ];
}
