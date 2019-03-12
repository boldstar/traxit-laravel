<?php

namespace App\Http\Middleware;

use Closure;
use Hyn\Tenancy\Models\Website;
use App\Models\System\Hostname;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ApiLogin
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
        if($request->fqdn != null) {
            return $next($request);
        }

        $environment = app(\Hyn\Tenancy\Environment::class);
        $websites = Website::all();
        foreach($websites as $website) {
            $hostname = Hostname::where('website_id', $website->id)->first();
            $environment->tenant($website);
            $user = DB::table('users')->where('email', $request->username)->first();
            if($user) {
                $environment->hostname($hostname);
                Config::set('database.default', 'tenant');
                return $next($request);
            }
        }

        return response('No Such User Exists, Please Try Again', 401);
        
    }
}
