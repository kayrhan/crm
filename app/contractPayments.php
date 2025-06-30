<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class contractPayments extends Model {
    use SoftDeletes;

    protected $table = 'contract_payments';
    protected $guarded = [];
    public $timestamps = true;
    protected $casts = [
        "deleted_at" => "datetime"
    ];
}