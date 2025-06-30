<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingTr extends Model
{
    use SoftDeletes;
    protected $table = "accounting_tr";
    public function getCustomerName(){
        return $this->hasOne("App\Organization","id","customer_id");
    }
    public function getCompanyName(){
        return $this->hasOne("App\Company","id","company_id");
    }

    public function getTicket(){
        return $this->hasOne("App\Ticket","id","ticket_id");
    }

    public function getOfficialInvoiceFromProforma(){
        return $this->hasOne("App\InvoiceReminderTr","invoice_no","no");
    }
    public function getOfficialInvoiceFromOffer(){
        return $this->hasOne("App\InvoiceReminderTr","invoice_no","proforma_no");
    }
    public function ticketIds(){
        return $this->hasMany("App\AccountingTrTicketId","accounting_id","id");
    }
}
