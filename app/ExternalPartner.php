<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalPartner extends Model
{
    use SoftDeletes;
    protected $table = "external_partners";
}
