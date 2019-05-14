<?php

namespace App\Models\Tenant;

use Illuminate\Support\Facades\Artisan;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
/**
 * @property Website website
 * @property Hostname hostname
 */
 
class Tenant
{
    public function __construct(Website $website = null, Hostname $hostname = null)
    {
        
        $this->website = $website ?? $sub->website;
        $this->hostname = $hostname ?? $sub->websites->hostnames->first();
       
    }
    
    public function delete()
    {
        app(HostnameRepository::class)->delete($this->hostname, true);
        app(WebsiteRepository::class)->delete($this->website, true);
    }
    
    public static function create($request): Tenant
    {
        // check that host is not duplicated
        if(Hostname::where('subdomain', $request->fqdn)->exists()) {
            throw new \Exception ('That tenant already exists');
        }

        // Create New Website
        $website = new Website;
        $website->company = $request->company;
        $website->email = $request->company_email;
        $website->number = $request->company_number;
        app(WebsiteRepository::class)->create($website);
        
        // associate the website with a hostname
        $hostname = new Hostname;
        $hostname->subdomain = $request->fqdn;
        $request->merge(['fqdn' => $request->fqdn . '.' . env('APP_URL_BASE')]);
        $hostname->fqdn = $request->fqdn;
        app(HostnameRepository::class)->attach($hostname, $website);
        
        // make hostname current
        app(Environment::class)->tenant($website);
        
        Artisan::call('passport:install');
        
        return new Tenant($website, $hostname);
    }
}

