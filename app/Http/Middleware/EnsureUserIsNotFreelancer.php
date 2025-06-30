<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsNotFreelancer { // Giriş yapmış olan kullanıcının "Freelancer" olup olmadığını bu middleware aracılığıyla kontrol edeceğiz ve filtreleyeceğiz.
    public function handle($request, Closure $next) {
        if(Auth::user()->role_id === 7) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}