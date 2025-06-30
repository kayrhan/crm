<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;


class CleanTemporaryFiles extends Command {

    protected $signature = 'CleanTemporaryFiles';

    protected $description = 'Clean Temporary Files daily at 3 am';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $file = new Filesystem;
        $file->cleanDirectory('storage/app/tempfiles');
        return 0;
    }
}
