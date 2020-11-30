<?php

namespace App\Http\Middleware;

use App\Services\LocaleService;
use Closure;
use Illuminate\Support\Facades\View;

class Locale
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
        if ($request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
        } else {
            $locale = config('app.locale');
            $request->session()->put('locale', $locale);
        }

        app()->setLocale($locale);

        View::share('locale', new LocaleService());

        return $next($request);
    }
}
