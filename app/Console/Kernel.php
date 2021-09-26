<?php

namespace App\Console;

use App\Models\Tenant\Integration;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {    
        // $schedule->call(function () {
        //     $integration = Integration::where('name', 'rubex')->first();

            
        //     $response = Http::withHeaders([
        //         'Content-Type' => 'application/x-www-form-urlencoded'
        //     ])
        //     ->post('https://rubex.efilecabinet.net/token', [
        //         grant_type => 'refresh_token',
        //         refresh_token => $integration->refresh_token,
        //         client_id => '1046',
        //         client_secret => '517ECA92-17B7-4311-882C-00C630223F4A'
        //     ]);

        //     $integration->update($response->data);
        // })->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
