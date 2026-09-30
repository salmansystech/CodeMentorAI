<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RateLimitMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $key = $this->resolveKey($request);
        $limit = auth()->check() ? 100 : 30;
        $window = 60;

        if ($this->tooManyAttempts($key, $limit, $window)) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $this->getRetryAfter($key, $window),
            ], 429);
        }

        $this->incrementAttempts($key, $window);

        return $next($request)->header('X-RateLimit-Limit', $limit)
            ->header('X-RateLimit-Remaining', $this->getRemaining($key, $limit))
            ->header('X-RateLimit-Reset', $this->getReset($key, $window));
    }

    protected function resolveKey(Request $request)
    {
        if (auth()->check()) {
            return 'rate-limit:' . auth()->id();
        }

        return 'rate-limit:' . $request->ip();
    }

    protected function tooManyAttempts($key, $limit, $window)
    {
        $attempts = Cache::get($key, 0);
        return $attempts >= $limit;
    }

    protected function incrementAttempts($key, $window)
    {
        $attempts = Cache::get($key, 0);
        Cache::put($key, $attempts + 1, now()->addSeconds($window));
    }

    protected function getRemaining($key, $limit)
    {
        return max(0, $limit - Cache::get($key, 0));
    }

    protected function getRetryAfter($key, $window)
    {
        $ttl = Cache::store()->connection()->ttl($key);
        return $ttl > 0 ? $ttl : $window;
    }

    protected function getReset($key, $window)
    {
        return now()->addSeconds($window)->timestamp;
    }
}
