<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hyn\Tenancy\Contracts\Hostname as Contract;
use Laravel\Cashier\Billable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hostname extends Authenticatable implements Contract
{
    use Billable;

    protected $fillable = [
        'id', 'stripe_id'
    ];

    
    /**
     * @property int $id
     * @property string $fqdn
     * @property string $redirect_to
     * @property bool $force_https
     * @property Carbon $under_maintenance_since
     * @property Carbon $created_at
     * @property Carbon $updated_at
     * @property Carbon $deleted_at
     * @property int $website_id
     * @property Website $website
     */

     public function website(): BelongsTo {;}

}
