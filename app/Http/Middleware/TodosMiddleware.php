<?php

namespace App\Http\Middleware;

use Closure;

class TodosMiddleware
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
         $user = auth()->user();

        if ($user->org_id == 7 || $user->org_id == 8 || $user->org_id == 3){ //
            return $next($request);
        }
        return redirect()->back();
    }
}
