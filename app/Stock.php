<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    protected $table = 'stocks';
    public $timestamps = true;

    use SoftDeletes;
}
