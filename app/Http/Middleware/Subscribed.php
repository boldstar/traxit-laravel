<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\System\Hostname as HostnameModel;
use App\Models\System\Subscription;

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
        
        if ($hostname && !Subscription::where('hostname_id', $hostname->id)->first()) {
            $hostModel = HostnameModel::where('fqdn', $hostname->fqdn)->first();
            // if host is still during trial period don't return subscribe view
            if($hostModel->onGenericTrial()) {
                return $next($request);
            }
            // This user is not a paying customer...
            return response()->view('subscribe');
        }

        return $next($request);
    
    }
}
