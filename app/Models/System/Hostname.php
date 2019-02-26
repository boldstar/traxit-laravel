<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hyn\Tenancy\Contracts\Hostname as Contract;
use Laravel\Cashier\Billable;


class Hostname extends Authenticatable implements Contract
{
    use Billable;
}
