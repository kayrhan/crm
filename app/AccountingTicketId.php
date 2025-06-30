<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingTicketId extends Model
{
    protected $table = "accounting_ticket_ids";
    protected $guarded=[];

    public function accountingId(){
        return $this->belongsTo("App\Accounting","accounting_id","id");
    }
}
