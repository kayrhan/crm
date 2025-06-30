<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contracts extends Model {
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "contracts";
    protected $guarded = [];
    protected $casts = [
        "deleted_at" => "datetime"
    ];
}