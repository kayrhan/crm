<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RedirectIfAuthenticated {
    public function handle(Request $request, Closure $next, ...$guards) {
        $guards = empty($guards) ? [null] : $guards;

        foreach($guards as $guard) {
            if(Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}