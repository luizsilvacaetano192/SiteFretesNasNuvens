<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ThrottleLoginAttempts
{
    public function handle($request, Closure $next)
    {
        if ($request->is('login') && $request->isMethod('post')) {
            $key = 'login.'.Str::lower($request->input('cnpj')).'|'.$request->ip();

            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                
                return response()->json([
                    'message' => 'Muitas tentativas. Tente novamente em '.$seconds.' segundos.',
                    'retry_after' => $seconds
                ], 429);
            }

            RateLimiter::hit($key, 60);
        }

        return $next($request);
    }
}