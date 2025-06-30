<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
    use SoftDeletes;
    public $timestamps = true;
    protected $guarded = [];
    protected $casts = [
        "deleted_at" => "datetime"
    ];
}