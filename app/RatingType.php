<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RatingType extends Model
{
    protected $table = "rating_types";
    public $timestamps = false;
    protected $fillable = ["name", "rating"];
}
