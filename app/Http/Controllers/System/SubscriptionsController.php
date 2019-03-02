<?php

namespace App\Http\Controllers\System;

use App\Models\System\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\System\Hostname;


class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Hostname::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        // $request->validate($request->all());

        $host = Hostname::where('fqdn', $request->fqdn)->first();

        $host->newSubscription('Pro', 'plan_EbORtk0B5aMtNg')->create($request->stripeToken, [
            'email' => $request->email,
        ]);

        return response()->json(['host' => $host, 'message' => 'A new subscription has been added!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invoices()
    {
        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();

        if($hostname->hasStripeId()) {
            $invoices = $hostname->invoices()->map(function($invoice) {
                return [
                    'date' => $invoice->date()->toFormattedDateString(),
                    'subscription' => $invoice->subscription,
                    'total' => '$' . number_format($invoice->total/100, 2),
                    'interval' => $invoice->interval,
                    'pdf' => $invoice->hosted_invoice_url,
                ];
            });
        } else {
            $invoices = [];
        }

        return [ 'invoices' => $invoices ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
