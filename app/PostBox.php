<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostBox extends Model
{
    use SoftDeletes;
    protected $table = "postbox";
}
