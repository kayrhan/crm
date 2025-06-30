<?php

namespace App;

use App\Events\PackageCreated;
use App\Events\PackageUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageTracking extends Model {
    use SoftDeletes;
    protected $table = "package_trackings";

    protected $dispatchesEvents = [
        "created" => PackageCreated::class,
        "updated" => PackageUpdated::class
    ];
}