<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;

class AccountingMiddleware {
    public function handle($request, Closure $next) {
        if(!in_array(auth()->id(), [5, 86, 119, 158, 161, 199, 201, 202])) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}