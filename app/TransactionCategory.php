<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionCategory extends Model
{
    use SoftDeletes;

    protected $table = "transaction_categories";
}
