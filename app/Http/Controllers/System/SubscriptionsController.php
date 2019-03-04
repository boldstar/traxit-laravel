<?php

namespace App\Http\Controllers\System;

use App\Models\System\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\System\Hostname;
use \Stripe\Plan;
use \Stripe\Stripe;


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
    public function plans()
    {
        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();

        $stripe = $hostname->subscription('Pro');

        Stripe::setApiKey(config('services.stripe.secret'));

        $plan = Plan::retrieve($stripe->stripe_plan);
        $plan->amount = '$' . number_format($plan->amount/100, 2);
        $plans = Plan::all();

        return response()->json(['plan' => $plan, 'plans' => $plans]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hostname  = Hostname::where('stripe_id', $id)->first();

        $stripe = $hostname->subscription('Pro');

        Stripe::setApiKey(config('services.stripe.secret'));

        $plan = Plan::retrieve($stripe->stripe_plan);
        $plan->amount = '$' . number_format($plan->amount/100, 2);

        return response()->json(['plan' => $plan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upgrade(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function resume()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();
        
        Stripe::setApiKey(config('services.stripe.secret'));
        
        // $stripe = $hostname->subscription('Pro')->cancel();

        return response()->view('subscribe');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelByAdmin($id)
    {
        $hostname  = Hostname::where('stripe_id', $id)->first();
        
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $stripe = $hostname->subscription('Pro')->cancel();

        return response()->json(['message' => 'Subscription Was Canceled']);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resumeByAdmin($id)
    {
        $hostname  = Hostname::where('stripe_id', $id)->first();
        
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $stripe = $hostname->subscription('Pro')->resume();

        return response()->json(['message' => 'Subscription Was Resumed']);
        
    }
}
