<?php

namespace App\Models\Tenant;;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Document extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'client_id',
        'document_name',
        'path',
        'payment_required',
        'signature_required',
        'signed',
        'paid',
        'downloadable',
        'message',
        'account',
        'uploaded_by',
        'tax_year'
    ];

    public function client()
    {
        return $this->belongsTo('App\Models\Tenant\Client');
    }
}
