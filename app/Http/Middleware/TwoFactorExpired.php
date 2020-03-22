<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant\TwoAuth;
use Carbon\Carbon;

class TwoFactorExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = TwoAuth::where('email', $request->email)->first();

        if(count($token) > 0 && Carbon::parse($token->expires_on)->isPast()) {
            $token->delete();
            return response('Confirmation time has expired, please try again.',  401);
        };


        return $next($request);
    }
}
