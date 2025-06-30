<?php

namespace App\Http\Middleware;

use App\Role;
use Closure;

class SuperAdminMiddleware
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

        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2) { // super admin and admin
            return $next($request);
        }
        return redirect('/tickets');
    }
}
