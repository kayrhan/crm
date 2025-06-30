<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accounting extends Model
{
    use SoftDeletes;
    protected $table = "accounting";

    public function getCustomerName(){
        return $this->hasOne("App\Organization","id","customer_id");
    }
    public function getCompanyName(){
        return $this->hasOne("App\Company","id","company_id");
    }

    public function getTicket(){
        return $this->hasOne("App\Ticket","id","ticket_id");
    }
    public function ticketIds(){
        return $this->hasMany("App\AccountingTicketId","accounting_id","id");
    }

}
