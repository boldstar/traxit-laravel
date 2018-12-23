<?php

namespace App\Http\Middleware;

use Closure;

class PreflightResponses
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
        if($request->getMethod() == 'OPTIONS'){

            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
            header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Key, Authorization');
            header('Access-Control-Allow-Credentials: true');
            exit(0);
        }
        return $next($request);
    }
}
