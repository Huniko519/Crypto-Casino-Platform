<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class TwoFactorAuthentication
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
        $user = $request->user();
        $route = Route::currentRouteName();

        // If 2FA is enabled and not yet passed
        if ($user->totp_secret && $request->session()->get('2fa_passed', 0) != 1 && $route != 'frontend.login.2fa') {
            return redirect()->route('frontend.login.2fa');
        // If 2FA is disabled or user already passed it and 2FA page is accessed
        } elseif ((!$user->totp_secret || $request->session()->get('2fa_passed', 0) == 1) && $route == 'frontend.login.2fa') {
            return redirect()->route('frontend.index');
        }

        return $next($request);
    }
}
