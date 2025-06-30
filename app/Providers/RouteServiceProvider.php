<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RouteServiceProvider extends ServiceProvider {
    public const HOME = "/tickets";

    public function boot() {
        $this->configureRateLimiting();
        $this->routes(function() {
            Route::prefix("api")->middleware("api")->group(base_path("routes/api.php"));
            Route::middleware("web")->group(base_path("routes/web.php"));
        });
    }

    protected function configureRateLimiting() {
        RateLimiter::for("api", function(Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}