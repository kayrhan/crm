<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractAttachments extends Model {
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "contract_attachments";
    protected $guarded = [];
    protected $casts = [
        "deleted_at" => "datetime"
    ];
}