<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\TwoAuth;
use App\Http\Controllers\Controller;
use App\Mail\Send2faToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    public function createCode(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|string'
        ]);

        $token = TwoAuth::where('email', $request->email)->first();
        if(count($token) > 0) {
            $token->delete();
        }

        $two_auth = TwoAuth::create([
            'email' => $request->email,
            'token' => mt_rand(10000,99999),
            'expires_on' => \Carbon\Carbon::now()->addMinutes(30)
        ]);

        Mail::to($request->email)->send(new Send2faToken($two_auth->token));

        return response('Token Created');
    }

    public function createNewCode(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|string'
        ]);

        $token = TwoAuth::where('email', $request->email)->first();
        if(count($token) > 0) {
            $token->delete();
        }

        $two_auth = TwoAuth::create([
            'email' => $request->email,
            'token' => mt_rand(10000,99999),
            'expires_on' => \Carbon\Carbon::now()->addMinutes(30)
        ]);

        Mail::to($request->email)->send(new Send2faToken($two_auth->token));

        return response('Token Created');
    }

    public function confirmCode(Request $request)
    {
        $token = TwoAuth::where('token', $request->code)->first();

        if(count($token) > 0) {
            $token->delete();
            return response('Token Confirmed.', 200);
        } else {
            return response('Please try again or request new code.', 405);
        }
    }
}
