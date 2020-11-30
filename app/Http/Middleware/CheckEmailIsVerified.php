<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class CheckEmailIsVerified
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
        // check that email is verified (in case email verification is enabled in the settings)
        if (config('settings.users.email_verification') && !$request->user()->hasVerifiedEmail() && !$request->user()->admin()) {
            return $request->expectsJson()
                ? response()->json(['error' => __('Please verify your email address.')])
                : Redirect::route('verification.notice');
        }

        return $next($request);
    }
}
