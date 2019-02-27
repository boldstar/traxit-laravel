<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Models\Hostname as Contract;
use Laravel\Cashier\Billable;

class Hostname extends Contract
{
    use Billable;

}
