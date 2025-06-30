<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceReminder extends Model {
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "invoice_reminder";
    protected $guarded = [];
    protected $casts = [
        "deleted_at" => "datetime"
    ];

    public function getStornoNumberAttribute() {
        $storno = AccountingStorno::where("invoice_no", substr($this->invoice_no, 3))->value('no');
        if($storno) {
            return $storno;
        }
        else {
            return null;
        }
    }

    protected $appends = [
        "StornoNumber",
    ];
}