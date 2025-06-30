<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    public $timestamps = false;
    protected $fillable = ["name"];
}
