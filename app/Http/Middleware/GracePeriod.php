<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\System\Subscription;
use \Stripe\Subscription as StripeSubscription;
use \Carbon\Carbon;
use \Stripe\Stripe;

class GracePeriod
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
        if($hostname && $hostname->subscribed('main')) {
            if ($hostname && $hostname->subscription('main')->onGracePeriod()) {
                Stripe::setApiKey(config('services.stripe.secret'));
                $stripe = $hostname->subscription('main');
                $subscription = StripeSubscription::retrieve($stripe->stripe_id);
                $date = Carbon::createFromTimeStamp($subscription->cancel_at)->toDateTimeString();
                $subscription->cancel_at = Carbon::parse($date)->format('m/d/Y');
                // This user is not a paying customer...
                return response()->json(['data' => $subscription]);
            }
        }
        
        return $next($request);
    }
}
