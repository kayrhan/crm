<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceReminderTr extends Model {
    use SoftDeletes;

    public $timestamps = true;
    protected $table = "invoice_reminder_tr";
    protected $guarded = [];
    protected $casts = [
        "deleted_at" => "datetime"
    ];
}