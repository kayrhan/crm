<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Model {
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "calendar";
    protected $guarded = [];
    protected $casts = [
        "deleted_at" => "datetime"
    ];
}