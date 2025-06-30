<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountingStorno extends Model
{
    protected $table = "accounting_stornos";

    public function getInvoice(){
        $this->hasOne("App\Accounting","storno_no","no");
    }
}
