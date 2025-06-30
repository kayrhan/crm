<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider {
    public function register() {
        //
    }

    public function boot() {
        Paginator::useBootstrap();
        Blade::if("role", function($roles) {
            return in_array(auth()->user()->role_id, $roles);
        });
    }
}