<?php

namespace Packages\Installer\Http\Middleware;

use Closure;

class RedirectIfInstalled
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
        // redirect to home page if the app is installed
        // important to check that there is no app_redirect session variable, otherwise user will not see installation complete page.
        if (file_exists(base_path() . '/storage/installed') && !$request->session()->has('app_redirect'))
            return redirect()->route('frontend.index');

        return $next($request);
    }
}
