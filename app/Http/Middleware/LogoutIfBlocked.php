<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class LogoutIfBlocked
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
        // log out user if the user is blocked
        if ($request->user()->status != User::STATUS_ACTIVE) {
            auth()->logout();
            return redirect()->route('login')->with('error', __('You are blocked.'));
        }

        return $next($request);
    }
}
