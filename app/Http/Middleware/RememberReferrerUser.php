<?php

namespace App\Http\Middleware;

use Closure;

class RememberReferrerUser
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
        $response = $next($request);

        // Check if referrer user ID is provided and it's not stored in cookie yet
        if (!$request->hasCookie('ref') && $request->query('ref') ) {
            // Add a cookie with 1 year duration
            $response->cookie('ref', encrypt($request->query('ref')), 525600);
        }

        return $response;
    }
}
