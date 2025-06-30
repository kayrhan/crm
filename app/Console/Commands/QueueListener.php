<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class QueueListener extends Command {
    protected $signature = "QueueListener";
    protected $description = "This command handles the jobs on the queue. If there is no job on the queue, it terminates itself.";

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        Artisan::call("queue:work --stop-when-empty --tries=3");
    }
}