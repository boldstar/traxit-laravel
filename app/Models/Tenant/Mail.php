<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Mail extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'id',
        'name',
        'to',
        'from',
        'subject',
        'message',
        'attachments',
        'path',
        'archived',
        'expires_on'
    ];
}
