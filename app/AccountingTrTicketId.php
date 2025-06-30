<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingTrTicketId extends Model
{
    protected $table = "accounting_tr_ticket_ids";
    protected $guarded=[];
    public function accountingId(){
        return $this->belongsTo("App\AccountingTr","accounting_id","id");
    }
}
