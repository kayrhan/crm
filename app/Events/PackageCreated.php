<?php

namespace App\Events;

use App\PackageTracking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PackageCreated {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public mixed $package;

    public function __construct(PackageTracking $packageTracking) {
        $this->package = $packageTracking;
    }
}