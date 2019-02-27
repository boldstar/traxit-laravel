<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HostnameServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(
        //     'App\Models\System\Hostname',
        //     'Hyn\Tenancy\Contacts\Hostname'
        // );


        // $this->app->bind(\Hyn\Tenancy\Contacts\Hostname::class, function(){

        //     return new \App\Models\System\Hostname;
        // });
    }
}
