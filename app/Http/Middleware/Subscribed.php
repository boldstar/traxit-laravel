<?php

namespace App\Http\Middleware;

use Closure;

class Subscribed
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
        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();

        if ($hostname && !$hostname->subscribed('Pro')) {
            // This user is not a paying customer...
            return response()->view('subscribe');
        }

        return $next($request);
    }
}
