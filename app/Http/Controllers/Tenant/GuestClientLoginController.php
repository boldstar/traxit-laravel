<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Guest;
use App\Models\Tenant\Client;

class GuestClientLoginController extends Controller
{
    public function guestLogin()
    {
        return response('guest');
    }
}
