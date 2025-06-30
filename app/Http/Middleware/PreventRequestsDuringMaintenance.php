<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;

class PreventRequestsDuringMaintenance extends Middleware {
    protected $except = [];
}