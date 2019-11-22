<?php

namespace App\Http\Middleware;

use Closure;

class ProviderDetectorMiddleware
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
        $validator = validator()->make($request->all(), [
            'username' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->getMessageBag(),
                'status_code' => 422
            ], 422);
        }

        config(['auth.guards.api.provider' => 'guest']);

        return $next($request);
    }
}
