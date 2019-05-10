<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Models\Hostname as Contract;
use Laravel\Cashier\Billable;

class Hostname extends Contract
{
    use Billable;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'trial_ends_at'
    ];
}
