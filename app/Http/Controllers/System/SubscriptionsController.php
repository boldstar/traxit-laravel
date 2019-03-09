<?php

namespace App\Http\Controllers\System;

use App\Models\System\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\System\Hostname;
use \Stripe\Plan;
use \Stripe\Subscription as StripeSubscription;
use \Stripe\Stripe;
use \Carbon\Carbon;


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
     * Store a new subscription for a company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        // $request->validate($request->all());

        $host = Hostname::where('fqdn', $request->fqdn)->first();

        if ($host->subscribed('main')) {
            return response(['message' => 'The Company Has Already Been Subscribed To A Plan']);
        };

        try {
            $host->newSubscription('main', $request->plan)->create($request->stripeToken, [
                'email' => $request->email,
            ]);
    
            return response()->json(['host' => $host, 'message' => 'A new subscription has been added!']);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

    }

    /**
     * The invoices for the customer.
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
     * Show the current subsription of the customer on the frontend.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function plans()
    {
        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();

        $stripe = $hostname->subscription('main');

        Stripe::setApiKey(config('services.stripe.secret'));

        $plans = Plan::all();
        foreach($plans as $plan) {
            $plan->amount = '$' . number_format($plan->amount/100, 2);
        };

        $plan = Plan::retrieve($stripe->stripe_plan);
        $plan->amount = '$' . number_format($plan->amount/100, 2);

        $subscription = StripeSubscription::retrieve($stripe->stripe_id);

        return response()->json(['plan' => $plan, 'plans' => $plans, 'subscription' => $subscription]);
    }

    /**
     * Show the current subsription of the customer on the frontend.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscriptionPlans()
    {

        Stripe::setApiKey(config('services.stripe.secret'));
        
        $plans = Plan::all();
        foreach($plans as $plan) {
            $plan->amount = '$' . number_format($plan->amount/100, 2);
        };

        return response($plans);
    }

    /**
     * Show the current subscription of the customer on the backend.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hostname  = Hostname::where('stripe_id', $id)->first();

        $stripe = $hostname->subscription('main');

        Stripe::setApiKey(config('services.stripe.secret'));

        $plan = Plan::retrieve($stripe->stripe_plan);
        $subscription = StripeSubscription::retrieve($stripe->stripe_id);
        $plan->amount = '$' . number_format($plan->amount/100, 2);

        return response()->json(['plan' => $plan, 'subscription' => $subscription]);
    }

    /**
     * Upgrade subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function upgrade(Request $request)
    {
        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();
        
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $stripe = $hostname->subscription('main')->swap($request->product);
    
            return response()->json(['message' => 'Your Plan Has Changed, Please Refresh!']);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
        
    }

    /**
     * Resume subscription from front end.
     *
     * @return \Illuminate\Http\Response
     */
    public function resume()
    {
        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();
        
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $stripe = $hostname->subscription('main')->resume();
            $subscription = StripeSubscription::retrieve($stripe->stripe_id);
    
            return response()->json(['message' => 'Your account has been resumed!', 'subscription' => $subscription]);
        } catch(\Exception $e) {
            return $d->getMessage();
        }
        
    }

    /**
     * Cancel subscription from frontend.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();
        
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $stripe = $hostname->subscription('main')->cancel();
    
            $subscription = StripeSubscription::retrieve($stripe->stripe_id);
            $date = Carbon::createFromTimeStamp($subscription->cancel_at)->toDateTimeString();
            $subscription->cancel_at = Carbon::parse($date)->format('m/d/Y');
    
            return response()->view('grace',['subscription' => $subscription]);
        } catch(\Exception $e) {
            $e->getMessage();
        }
        
        
    }

    /**
     * Cancel subscription from backend.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelByAdmin($id)
    {
        $hostname  = Hostname::where('stripe_id', $id)->first();
        
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $stripe = $hostname->subscription('main')->cancel();
    
            return response()->json(['message' => 'Subscription Was Canceled']);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
        
        
    }

    /**
     * Resume subscription from backend.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resumeByAdmin($id)
    {
        $hostname  = Hostname::where('stripe_id', $id)->first();
        
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $stripe = $hostname->subscription('main')->resume();
    
            return response()->json(['message' => 'Subscription Was Resumed']);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
        
        
    }

    /**
     * Upgrade card
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCard(Request $request) {
        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();
        
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $hostname->updateCard($request->stripeToken);
    
            return response()->json(['message' => 'Your card was updated!']);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
        
    }

    /**
     * this is a dummy call for the grace period middleware
     */
    public function gracePeriod() {
        $bool = 'false';
        return response($bool);
    }

    /**
     * return the stripe key to front end
     */
    public function stripeKey() {
        $key = config('services.stripe.key');
        return response($key);
    }
}
