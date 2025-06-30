<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceReminderAttachmentsTr extends Model {
    use SoftDeletes;

    protected $table = "invoice_reminder_attachments_tr";
    protected $guarded = [];
    public $timestamps = true;
    protected $casts = [
        "deleted_at" => "datetime"
    ];
}