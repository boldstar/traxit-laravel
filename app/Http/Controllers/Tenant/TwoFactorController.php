<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\TwoAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function createCode(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|string'
        ]);

        TwoAuth::create([
            'email' => $request->email,
            'token' => mt_rand(10000,99999),
            'expires_on' => \Carbon\Carbon::now()->addMinutes(30)
        ]);

        return response('Token Created');
    }
}
