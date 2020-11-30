<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlockConcurrentRequests
{
    private $cacheKey;

    public function __construct(Request $request)
    {
        $this->cacheKey = 'users.' . $request->user()->id . '.requests_count';
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $count = Cache::get($this->cacheKey, 0);
        Cache::put($this->cacheKey, ++$count, 0.5);

        // if there are more concurrent requests than 1
        if ($count > 1) {
            abort(429);
        }

        return $next($request);
    }

    public function terminate($request, $response)
    {
        $count = Cache::get($this->cacheKey, 0);
        Cache::put($this->cacheKey, --$count, 0.5);
    }
}
