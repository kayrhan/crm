<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingData extends Model
{
    use SoftDeletes;
    protected $table = "accounting_data";

}
