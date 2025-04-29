<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class CleanExpiredLoginAttempts
{
    public function handle($request, Closure $next)
    {
        if ($request->is('login') && $request->isMethod('post')) {
            $key = 'login_attempts:'.md5(Str::lower($request->input('cnpj')).'|'.$request->ip());
            
            // Limpa tentativas expiradas
            if (RateLimiter::remaining($key, 5) === 5) {
                RateLimiter::clear($key);
            }
        }

        return $next($request);
    }
}