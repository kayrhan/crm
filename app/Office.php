<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    protected $table = 'offices';
    public $timestamps = true;

    use SoftDeletes;
}
